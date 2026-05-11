import { Star } from "lucide-react";
import type { Book } from "@/lib/library-data";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";

export function BookCard({ book }: { book: Book }) {
  const out = book.available === 0;
  return (
    <div className="group rounded-xl bg-card border border-border overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
      <div className={`aspect-[3/4] bg-gradient-to-br ${book.cover} relative p-5 flex flex-col justify-between`}>
        <div className="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(255,255,255,0.15),transparent_50%)]" />
        <div className="relative">
          <Badge className="bg-white/15 text-white border-white/20 backdrop-blur hover:bg-white/20">{book.genre}</Badge>
        </div>
        <div className="relative text-white">
          <div className="font-display text-xl leading-tight drop-shadow">{book.title}</div>
          <div className="text-xs text-white/80 mt-1">{book.author}</div>
        </div>
        <div className="absolute right-3 top-3 h-full w-px bg-white/10" />
      </div>
      <div className="p-4 space-y-3">
        <div className="flex items-center justify-between text-sm">
          <div className="flex items-center gap-1 text-foreground">
            <Star className="h-3.5 w-3.5 fill-accent text-accent" />
            <span className="font-medium">{book.rating}</span>
            <span className="text-muted-foreground">· {book.year}</span>
          </div>
          <span className={`text-xs font-medium ${out ? "text-destructive" : "text-primary"}`}>
            {out ? "All checked out" : `${book.available}/${book.total} available`}
          </span>
        </div>
        <Button className="w-full" variant={out ? "secondary" : "default"} disabled={out}>
          {out ? "Join waitlist" : "Borrow"}
        </Button>
      </div>
    </div>
  );
}
