import { createFileRoute } from "@tanstack/react-router";
import { useQuery } from "@tanstack/react-query";
import { Button } from "@/components/ui/button";
import { Mail } from "lucide-react";
import { AuthGuard } from "@/components/auth-guard";
import { apiFetch } from "@/lib/auth-context";

export const Route = createFileRoute("/members")({
  head: () => ({ meta: [{ title: "Members — SmartShelf" }] }),
  component: Members,
});

function avatar(name: string) {
  if (!name) return "??";
  return name.split(" ").map((n) => n[0]).slice(0, 2).join("").toUpperCase();
}

interface MemberData {
  id: number;
  membership_id: string;
  membership_status: string;
  joined_at: string;
  user: {
    name: string;
    email: string;
  };
}

interface PaginatedMembers {
  data: MemberData[];
  total: number;
}

function Members() {
  const { data: membersData, isLoading } = useQuery<PaginatedMembers>({
    queryKey: ["members"],
    queryFn: () => apiFetch("/members"),
  });

  const members = membersData?.data || [];
  const total = membersData?.total || 0;

  return (
    <AuthGuard>
    <div className="space-y-8">
      <div className="flex items-end justify-between">
        <div>
          <div className="text-xs uppercase tracking-[0.2em] text-accent">Community</div>
          <h1 className="font-display text-4xl mt-2">Members</h1>
          <p className="text-muted-foreground mt-2">{total} registered readers.</p>
        </div>
        <Button>Register member</Button>
      </div>

      {isLoading ? (
        <div className="text-center py-20 text-muted-foreground">Loading members...</div>
      ) : members.length === 0 ? (
        <div className="text-center py-20 text-muted-foreground">No members found.</div>
      ) : (
        <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
          {members.map((m) => (
            <div key={m.id} className="rounded-xl border border-border bg-card p-5 hover:border-accent transition-colors">
              <div className="flex items-start gap-4">
                <div className="h-12 w-12 rounded-full bg-gradient-to-br from-primary to-primary/70 text-primary-foreground flex items-center justify-center font-medium">
                  {avatar(m.user?.name)}
                </div>
                <div className="flex-1 min-w-0">
                  <div className="font-display text-lg leading-tight truncate">{m.user?.name || "Unknown"}</div>
                  <div className="text-xs text-muted-foreground font-mono">{m.membership_id}</div>
                </div>
                <span className={`text-xs px-2 py-1 rounded-full capitalize ${m.membership_status === "active" ? "bg-accent/15 text-accent-foreground" : "bg-muted text-muted-foreground"}`}>
                  {m.membership_status}
                </span>
              </div>
              <div className="mt-4 pt-4 border-t border-border space-y-2 text-sm">
                <div className="flex items-center gap-2 text-muted-foreground truncate">
                  <Mail className="h-3.5 w-3.5 shrink-0" /> {m.user?.email}
                </div>
                <div className="text-xs text-muted-foreground">Member since {new Date(m.joined_at).toLocaleDateString()}</div>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
    </AuthGuard>
  );
}
