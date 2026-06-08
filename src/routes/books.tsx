import { createFileRoute } from "@tanstack/react-router";
import { useQuery } from "@tanstack/react-query";
import { AuthGuard } from "@/components/auth-guard";
import { apiFetch } from "@/lib/auth-context";
import { Button } from "@/components/ui/button";

export const Route = createFileRoute("/books")({
  head: () => ({ meta: [{ title: "Books — SmartShelf" }] }),
  component: Books,
});

function Books() {
  const { data: booksData, isLoading } = useQuery<{data: any[]}>({
    queryKey: ["manage-books"],
    queryFn: () => apiFetch("/books"),
  });

  const books = booksData?.data || [];

  return (
    <AuthGuard>
      <div className="space-y-8">
        <div className="flex items-end justify-between">
          <div>
            <div className="text-xs uppercase tracking-[0.2em] text-accent">Administration</div>
            <h1 className="font-display text-4xl mt-2">Manage Books</h1>
          </div>
          <Button>Add Book</Button>
        </div>

        {isLoading ? (
          <div className="text-center py-10 text-muted-foreground">Loading...</div>
        ) : (
          <div className="rounded-xl border border-border overflow-hidden bg-card">
            <table className="w-full text-sm text-left">
              <thead className="bg-muted/50 text-muted-foreground border-b border-border">
                <tr>
                  <th className="px-4 py-3 font-medium">Title</th>
                  <th className="px-4 py-3 font-medium">ISBN</th>
                  <th className="px-4 py-3 font-medium">Published</th>
                  <th className="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-border">
                {books.map((b: any) => (
                  <tr key={b.id} className="hover:bg-muted/20 transition-colors">
                    <td className="px-4 py-3 font-medium">{b.title}</td>
                    <td className="px-4 py-3 text-muted-foreground">{b.isbn}</td>
                    <td className="px-4 py-3 text-muted-foreground">{b.published_year}</td>
                    <td className="px-4 py-3 text-right">
                      <Button variant="ghost" size="sm">Edit</Button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>
    </AuthGuard>
  );
}
