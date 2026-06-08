import { createFileRoute, Link } from "@tanstack/react-router";
import { AuthGuard } from "@/components/auth-guard";
import { ArrowLeft } from "lucide-react";
import { useState, useEffect } from "react";
import { apiFetch } from "@/lib/auth-context";

export const Route = createFileRoute("/ebooks/$ebookId/read")({
  component: EbookRead,
});

function EbookRead() {
  const { ebookId } = Route.useParams();
  const [streamUrl, setStreamUrl] = useState<string>("");

  useEffect(() => {
    // In a real app we might fetch the ebook metadata first to show the title
    // Here we just construct the stream URL.
    // For protected files, we'd typically need to pass the Bearer token. 
    // An iframe directly accessing /api won't send the Bearer token unless stored in a cookie.
    // Assuming Sanctum cookie auth is used or we fetch the blob.
    
    // For now we assume the frontend proxy handles /api and sanctum uses cookies:
    setStreamUrl(`/api/ebooks/${ebookId}/stream`);
  }, [ebookId]);

  return (
    <AuthGuard>
      <div className="h-[calc(100vh-8rem)] flex flex-col">
        <div className="flex items-center gap-4 mb-4">
          <Link to="/ebooks" className="text-muted-foreground hover:text-foreground transition-colors flex items-center gap-2 text-sm font-medium">
            <ArrowLeft className="h-4 w-4" />
            Back to E-Books
          </Link>
          <div className="h-4 w-px bg-border" />
          <h1 className="font-display text-lg">Reading View</h1>
        </div>
        
        <div className="flex-1 rounded-xl border border-border overflow-hidden bg-card/50">
          {streamUrl ? (
            <iframe 
              src={streamUrl} 
              className="w-full h-full border-0" 
              title="E-Book Reader"
            />
          ) : (
            <div className="flex items-center justify-center h-full text-muted-foreground">
              Loading stream...
            </div>
          )}
        </div>
      </div>
    </AuthGuard>
  );
}
