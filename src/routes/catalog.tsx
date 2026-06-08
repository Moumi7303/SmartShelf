import { createFileRoute } from "@tanstack/react-router";
import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import { BookCard, type ApiBook } from "@/components/book-card";
import { Input } from "@/components/ui/input";
import { Search } from "lucide-react";
import { AuthGuard } from "@/components/auth-guard";
import { apiFetch } from "@/lib/auth-context";

export const Route = createFileRoute("/catalog")({
  head: () => ({ meta: [{ title: "Catalog — SmartShelf" }] }),
  component: Catalog,
});

interface PaginatedBooks {
  data: ApiBook[];
  total: number;
}

interface Category {
  id: number;
  name: string;
}

function Catalog() {
  const [categoryId, setCategoryId] = useState<number | null>(null);
  const [q, setQ] = useState("");

  const { data: booksData, isLoading: isLoadingBooks } = useQuery<PaginatedBooks>({
    queryKey: ["books", categoryId, q],
    queryFn: () => {
      const params = new URLSearchParams();
      if (categoryId) params.append("category_id", categoryId.toString());
      if (q) params.append("search", q);
      return apiFetch(`/books?${params.toString()}`);
    },
  });

  const { data: categories } = useQuery<Category[]>({
    queryKey: ["categories"],
    queryFn: () => apiFetch("/categories"),
  });

  const books = booksData?.data || [];
  const total = booksData?.total || 0;

  return (
    <AuthGuard>
    <div className="space-y-8">
      <div>
        <div className="text-xs uppercase tracking-[0.2em] text-accent">Catalog</div>
        <h1 className="font-display text-4xl mt-2">Browse the collection</h1>
        <p className="text-muted-foreground mt-2">{total} titles available in the catalog.</p>
      </div>

      <div className="flex flex-col md:flex-row gap-4 md:items-center">
        <div className="relative flex-1 max-w-md">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input 
            value={q} 
            onChange={(e) => setQ(e.target.value)} 
            placeholder="Search title or author…" 
            className="pl-9" 
          />
        </div>
        <div className="flex flex-wrap gap-2">
          <button
            onClick={() => setCategoryId(null)}
            className={`px-3 py-1.5 rounded-full text-xs font-medium border transition-colors ${
              categoryId === null ? "bg-primary text-primary-foreground border-primary" : "bg-card border-border hover:border-accent"
            }`}
          >
            All
          </button>
          {categories?.map((c) => (
            <button
              key={c.id}
              onClick={() => setCategoryId(c.id)}
              className={`px-3 py-1.5 rounded-full text-xs font-medium border transition-colors ${
                categoryId === c.id ? "bg-primary text-primary-foreground border-primary" : "bg-card border-border hover:border-accent"
              }`}
            >
              {c.name}
            </button>
          ))}
        </div>
      </div>

      {isLoadingBooks ? (
        <div className="text-center py-20 text-muted-foreground">Loading catalog...</div>
      ) : books.length === 0 ? (
        <div className="text-center py-20 text-muted-foreground">No books match your search.</div>
      ) : (
        <div className="grid gap-5 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
          {books.map((b) => <BookCard key={b.id} book={b} />)}
        </div>
      )}
    </div>
    </AuthGuard>
  );
}
