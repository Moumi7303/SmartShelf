import { createFileRoute } from "@tanstack/react-router";
import { useQuery } from "@tanstack/react-query";
import { AuthGuard } from "@/components/auth-guard";
import { apiFetch } from "@/lib/auth-context";
import { Button } from "@/components/ui/button";

export const Route = createFileRoute("/authors")({
  head: () => ({ meta: [{ title: "Authors — SmartShelf" }] }),
  component: Authors,
});

function Authors() {
  const { data: authorsData, isLoading } = useQuery<{data: any[]}>({
    queryKey: ["manage-authors"],
    queryFn: () => apiFetch("/authors"),
  });

  const authors = authorsData?.data || [];

  return (
    <AuthGuard>
      <div className="space-y-8">
        <div className="flex items-end justify-between">
          <div>
            <div className="text-xs uppercase tracking-[0.2em] text-accent">Administration</div>
            <h1 className="font-display text-4xl mt-2">Manage Authors</h1>
          </div>
          <Button>Add Author</Button>
        </div>

        {isLoading ? (
          <div className="text-center py-10 text-muted-foreground">Loading...</div>
        ) : (
          <div className="rounded-xl border border-border overflow-hidden bg-card">
            <table className="w-full text-sm text-left">
              <thead className="bg-muted/50 text-muted-foreground border-b border-border">
                <tr>
                  <th className="px-4 py-3 font-medium">Name</th>
                  <th className="px-4 py-3 font-medium">Bio Length</th>
                  <th className="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-border">
                {authors.map((a: any) => (
                  <tr key={a.id} className="hover:bg-muted/20 transition-colors">
                    <td className="px-4 py-3 font-medium">{a.name}</td>
                    <td className="px-4 py-3 text-muted-foreground">{a.bio?.length || 0} chars</td>
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
