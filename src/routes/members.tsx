import { createFileRoute } from "@tanstack/react-router";
import { members } from "@/lib/library-data";
import { Button } from "@/components/ui/button";
import { Mail } from "lucide-react";
import { AuthGuard } from "@/components/auth-guard";

export const Route = createFileRoute("/members")({
  head: () => ({ meta: [{ title: "Members — SmartShelf" }] }),
  component: Members,
});

function avatar(name: string) {
  return name.split(" ").map((n) => n[0]).slice(0, 2).join("");
}

function Members() {
  return (
    <AuthGuard>
    <div className="space-y-8">
      <div className="flex items-end justify-between">
        <div>
          <div className="text-xs uppercase tracking-[0.2em] text-accent">Community</div>
          <h1 className="font-display text-4xl mt-2">Members</h1>
          <p className="text-muted-foreground mt-2">{members.length} registered readers.</p>
        </div>
        <Button>Register member</Button>
      </div>

      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        {members.map((m) => (
          <div key={m.id} className="rounded-xl border border-border bg-card p-5 hover:border-accent transition-colors">
            <div className="flex items-start gap-4">
              <div className="h-12 w-12 rounded-full bg-gradient-to-br from-primary to-primary/70 text-primary-foreground flex items-center justify-center font-medium">
                {avatar(m.name)}
              </div>
              <div className="flex-1 min-w-0">
                <div className="font-display text-lg leading-tight">{m.name}</div>
                <div className="text-xs text-muted-foreground font-mono">{m.id}</div>
              </div>
              <span className={`text-xs px-2 py-1 rounded-full ${m.active > 0 ? "bg-accent/15 text-accent-foreground" : "bg-muted text-muted-foreground"}`}>
                {m.active} active
              </span>
            </div>
            <div className="mt-4 pt-4 border-t border-border space-y-2 text-sm">
              <div className="flex items-center gap-2 text-muted-foreground">
                <Mail className="h-3.5 w-3.5" /> {m.email}
              </div>
              <div className="text-xs text-muted-foreground">Member since {m.joined}</div>
            </div>
          </div>
        ))}
      </div>
    </div>
    </AuthGuard>
  );
}
