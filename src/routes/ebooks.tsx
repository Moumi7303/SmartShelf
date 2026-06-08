import { createFileRoute, Link } from "@tanstack/react-router";
import { useQuery } from "@tanstack/react-query";
import { AuthGuard } from "@/components/auth-guard";
import { apiFetch } from "@/lib/auth-context";
import { Button } from "@/components/ui/button";

export const Route = createFileRoute("/ebooks")({
  head: () => ({ meta: [{ title: "E-Books — SmartShelf" }] }),
  component: Ebooks,
});

function Ebooks() {
  const { data: ebooksData, isLoading } = useQuery<{data: any[]}>({
    queryKey: ["manage-ebooks"],
    queryFn: () => apiFetch("/ebooks"),
  });

  const ebooks = ebooksData?.data || [];

  return (
    <AuthGuard>
      <div className="space-y-8">
        <div className="flex items-end justify-between">
          <div>
            <div className="text-xs uppercase tracking-[0.2em] text-accent">Administration</div>
            <h1 className="font-display text-4xl mt-2">Manage E-Books</h1>
          </div>
          <Button>Upload E-Book</Button>
        </div>

        {isLoading ? (
          <div className="text-center py-10 text-muted-foreground">Loading...</div>
        ) : (
          <div className="rounded-xl border border-border overflow-hidden bg-card">
            <table className="w-full text-sm text-left">
              <thead className="bg-muted/50 text-muted-foreground border-b border-border">
                <tr>
                  <th className="px-4 py-3 font-medium">Book Title</th>
                  <th className="px-4 py-3 font-medium">Size</th>
                  <th className="px-4 py-3 font-medium">Access Level</th>
                  <th className="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-border">
                {ebooks.map((eb: any) => (
                  <tr key={eb.id} className="hover:bg-muted/20 transition-colors">
                    <td className="px-4 py-3 font-medium">{eb.book?.title || 'Unknown'}</td>
                    <td className="px-4 py-3 text-muted-foreground">{eb.file_size_formatted || `${Math.round(eb.file_size/1024)} KB`}</td>
                    <td className="px-4 py-3 text-muted-foreground capitalize">{eb.access_level}</td>
                    <td className="px-4 py-3 text-right flex justify-end gap-2">
                      <Link to={`/ebooks/${eb.id}/read`} className="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-8 px-3">
                        Read
                      </Link>
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
