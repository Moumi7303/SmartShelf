import { createFileRoute } from "@tanstack/react-router";
import { books, loans } from "@/lib/library-data";
import { Button } from "@/components/ui/button";

export const Route = createFileRoute("/loans")({
  head: () => ({ meta: [{ title: "Loans — Inside Love" }] }),
  component: Loans,
});

const statusStyles: Record<string, string> = {
  active: "bg-primary/10 text-primary",
  overdue: "bg-destructive/10 text-destructive",
  returned: "bg-muted text-muted-foreground",
};

function Loans() {
  return (
    <div className="space-y-8">
      <div className="flex items-end justify-between">
        <div>
          <div className="text-xs uppercase tracking-[0.2em] text-accent">Circulation</div>
          <h1 className="font-display text-4xl mt-2">Loans & returns</h1>
          <p className="text-muted-foreground mt-2">{loans.filter(l => l.status !== "returned").length} active loans, {loans.filter(l => l.status === "overdue").length} overdue.</p>
        </div>
        <Button>Issue new loan</Button>
      </div>

      <div className="rounded-xl border border-border bg-card overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead className="bg-muted/40 text-left text-xs uppercase tracking-wider text-muted-foreground">
              <tr>
                <th className="px-5 py-3 font-medium">Loan ID</th>
                <th className="px-5 py-3 font-medium">Book</th>
                <th className="px-5 py-3 font-medium">Member</th>
                <th className="px-5 py-3 font-medium">Borrowed</th>
                <th className="px-5 py-3 font-medium">Due</th>
                <th className="px-5 py-3 font-medium">Status</th>
                <th className="px-5 py-3"></th>
              </tr>
            </thead>
            <tbody>
              {loans.map((l) => {
                const book = books.find((b) => b.id === l.bookId);
                return (
                  <tr key={l.id} className="border-t border-border hover:bg-muted/20">
                    <td className="px-5 py-4 font-mono text-xs">{l.id}</td>
                    <td className="px-5 py-4">
                      <div className="font-medium">{book?.title}</div>
                      <div className="text-xs text-muted-foreground">{book?.author}</div>
                    </td>
                    <td className="px-5 py-4">{l.member}</td>
                    <td className="px-5 py-4 text-muted-foreground">{l.borrowed}</td>
                    <td className="px-5 py-4 text-muted-foreground">{l.due}</td>
                    <td className="px-5 py-4">
                      <span className={`text-xs px-2 py-1 rounded-full font-medium capitalize ${statusStyles[l.status]}`}>{l.status}</span>
                    </td>
                    <td className="px-5 py-4 text-right">
                      <button className="text-xs text-primary hover:underline">
                        {l.status === "returned" ? "View" : "Mark returned"}
                      </button>
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
