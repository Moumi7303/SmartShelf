import { createFileRoute } from "@tanstack/react-router";
import { useMemo, useState } from "react";
import { books, genres } from "@/lib/library-data";
import { BookCard } from "@/components/book-card";
import { Input } from "@/components/ui/input";
import { Search } from "lucide-react";

export const Route = createFileRoute("/catalog")({
  head: () => ({ meta: [{ title: "Catalog — SmartShelf" }] }),
  component: Catalog,
});

function Catalog() {
  const [genre, setGenre] = useState("All");
  const [q, setQ] = useState("");

  const filtered = useMemo(() => books.filter((b) => {
    const okGenre = genre === "All" || b.genre === genre;
    const okQ = !q || b.title.toLowerCase().includes(q.toLowerCase()) || b.author.toLowerCase().includes(q.toLowerCase());
    return okGenre && okQ;
  }), [genre, q]);

  return (
    <div className="space-y-8">
      <div>
        <div className="text-xs uppercase tracking-[0.2em] text-accent">Catalog</div>
        <h1 className="font-display text-4xl mt-2">Browse the collection</h1>
        <p className="text-muted-foreground mt-2">{books.length} titles across {genres.length - 1} categories.</p>
      </div>

      <div className="flex flex-col md:flex-row gap-4 md:items-center">
        <div className="relative flex-1 max-w-md">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input value={q} onChange={(e) => setQ(e.target.value)} placeholder="Search title or author…" className="pl-9" />
        </div>
        <div className="flex flex-wrap gap-2">
          {genres.map((g) => (
            <button
              key={g}
              onClick={() => setGenre(g)}
              className={`px-3 py-1.5 rounded-full text-xs font-medium border transition-colors ${
                genre === g ? "bg-primary text-primary-foreground border-primary" : "bg-card border-border hover:border-accent"
              }`}
            >{g}</button>
          ))}
        </div>
      </div>

      {filtered.length === 0 ? (
        <div className="text-center py-20 text-muted-foreground">No books match your search.</div>
      ) : (
        <div className="grid gap-5 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
          {filtered.map((b) => <BookCard key={b.id} book={b} />)}
        </div>
      )}
    </div>
  );
}
