import { Star } from "lucide-react";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";

export interface ApiBook {
  id: number;
  title: string;
  author: { name: string };
  category: { name: string };
  availability_label: string;
  cover_image_url?: string;
  publication_year?: number;
}

export function BookCard({ book }: { book: ApiBook }) {
  const out = book.availability_label === "All checked out" || book.availability_label === "No copies";
  const coverBg = book.cover_image_url ? `url(${book.cover_image_url})` : "";
  const fallbackGradient = "from-blue-500 to-indigo-600"; // Default fallback

  return (
    <div className="group rounded-xl bg-card border border-border overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
      <div 
        className={`aspect-[3/4] bg-gradient-to-br ${!book.cover_image_url ? fallbackGradient : ''} relative p-5 flex flex-col justify-between`}
        style={book.cover_image_url ? { backgroundImage: coverBg, backgroundSize: 'cover', backgroundPosition: 'center' } : {}}
      >
        <div className="absolute inset-0 bg-black/40" />
        <div className="relative">
          <Badge className="bg-white/15 text-white border-white/20 backdrop-blur hover:bg-white/20">{book.category?.name || 'Unknown'}</Badge>
        </div>
        <div className="relative text-white">
          <div className="font-display text-xl leading-tight drop-shadow">{book.title}</div>
          <div className="text-xs text-white/80 mt-1">{book.author?.name || 'Unknown'}</div>
        </div>
        <div className="absolute right-3 top-3 h-full w-px bg-white/10" />
      </div>
      <div className="p-4 space-y-3">
        <div className="flex items-center justify-between text-sm">
          <div className="flex items-center gap-1 text-foreground">
            <Star className="h-3.5 w-3.5 fill-accent text-accent" />
            <span className="font-medium">4.5</span>
            <span className="text-muted-foreground">· {book.publication_year || 'N/A'}</span>
          </div>
          <span className={`text-xs font-medium ${out ? "text-destructive" : "text-primary"}`}>
            {book.availability_label}
          </span>
        </div>
        <Button className="w-full" variant={out ? "secondary" : "default"} disabled={out}>
          {out ? "Join waitlist" : "Borrow"}
        </Button>
      </div>
    </div>
  );
}
