import { createFileRoute, Link } from "@tanstack/react-router";
import { BookOpen, Users, BookMarked, TrendingUp, ArrowRight, Clock, BarChart3 } from "lucide-react";
import { useQuery } from "@tanstack/react-query";
import { BookCard, type ApiBook } from "@/components/book-card";
import { AuthGuard } from "@/components/auth-guard";
import { useAuth, apiFetch } from "@/lib/auth-context";

export const Route = createFileRoute("/")({
  head: () => ({ meta: [{ title: "Dashboard — SmartShelf" }] }),
  component: DashboardPage,
});

function Stat({ icon: Icon, label, value, hint }: any) {
  return (
    <div className="rounded-xl border border-border bg-card p-5 hover:shadow-lg hover:shadow-primary/5 transition-all duration-300 group">
      <div className="flex items-center justify-between">
        <span className="text-xs uppercase tracking-wider text-muted-foreground">{label}</span>
        <div className="h-8 w-8 rounded-lg bg-accent/10 flex items-center justify-center group-hover:bg-accent/20 transition-colors">
          <Icon className="h-4 w-4 text-accent" />
        </div>
      </div>
      <div className="mt-3 font-display text-3xl">{value}</div>
      <div className="mt-1 text-xs text-muted-foreground">{hint}</div>
    </div>
  );
}

function DashboardPage() {
  return (
    <AuthGuard>
      <Dashboard />
    </AuthGuard>
  );
}

function Dashboard() {
  const { user } = useAuth();
  
  const { data: booksData } = useQuery<{data: ApiBook[], total: number}>({
    queryKey: ["books"],
    queryFn: () => apiFetch("/books"),
  });

  const { data: txData } = useQuery<{data: any[], total: number}>({
    queryKey: ["transactions"],
    queryFn: () => apiFetch("/transactions"),
  });

  const { data: membersData } = useQuery<{data: any[], total: number}>({
    queryKey: ["members"],
    queryFn: () => apiFetch("/members"),
  });

  const books = booksData?.data || [];
  const loans = txData?.data || [];
  
  const overdue = loans.filter((l) => l.status === "overdue");
  const active = loans.filter((l) => l.status === "issued");
  const featured = books.slice(0, 4);

  return (
    <div className="space-y-10">
      {/* Personalized greeting */}
      <section className="relative overflow-hidden rounded-2xl border border-border bg-gradient-to-br from-primary via-primary to-primary/80 text-primary-foreground p-8 md:p-12">
        <div className="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_80%_20%,var(--brass),transparent_50%)]" />
        <div className="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_20%_80%,oklch(0.6_0.15_250),transparent_40%)]" />
        <div className="relative max-w-2xl">
          <h1 className="font-display text-4xl md:text-5xl leading-tight">
            Welcome back{user?.name ? `, ${user.name.split(" ")[0]}` : ""}
          </h1>
          <p className="mt-3 text-primary-foreground/75 max-w-lg">
            Here's what's happening across your library network today. Track loans, manage collections, and monitor multi-branch analytics.
          </p>
          <div className="mt-6 flex flex-wrap gap-3">
            <Link to="/catalog" className="inline-flex items-center gap-2 rounded-lg bg-accent px-5 py-2.5 text-sm font-medium text-accent-foreground hover:opacity-90 shadow-lg shadow-accent/20 transition-all">
              Browse catalog <ArrowRight className="h-4 w-4" />
            </Link>
            <Link to="/loans" className="inline-flex items-center gap-2 rounded-lg border border-primary-foreground/20 px-5 py-2.5 text-sm font-medium hover:bg-primary-foreground/10 transition-colors">
              Manage loans
            </Link>
          </div>
        </div>
      </section>

      {/* Stats grid */}
      <section className="grid gap-4 grid-cols-2 lg:grid-cols-4">
        <Stat icon={BookOpen} label="Total Books" value={booksData?.total || 0} hint={`Across the catalog`} />
        <Stat icon={BookMarked} label="Active Loans" value={active.length} hint="Currently checked out" />
        <Stat icon={Clock} label="Overdue" value={overdue.length} hint="Needs follow-up" />
        <Stat icon={Users} label="Members" value={membersData?.total || 0} hint="Registered readers" />
      </section>

      {/* Featured books */}
      <section>
        <div className="flex items-end justify-between mb-5">
          <div>
            <h2 className="font-display text-2xl">Featured this week</h2>
            <p className="text-sm text-muted-foreground">Curated by our librarians.</p>
          </div>
          <Link to="/catalog" className="text-sm text-primary hover:underline flex items-center gap-1">View all <ArrowRight className="h-3 w-3" /></Link>
        </div>
        <div className="grid gap-5 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
          {featured.map((b) => <BookCard key={b.id} book={b} />)}
        </div>
      </section>

      {/* Recent loans + Quick actions */}
      <section className="grid gap-6 md:grid-cols-2">
        <div className="rounded-xl border border-border bg-card p-6">
          <div className="flex items-center justify-between mb-4">
            <h3 className="font-display text-xl">Recent loans</h3>
            <TrendingUp className="h-4 w-4 text-muted-foreground" />
          </div>
          <ul className="space-y-3">
            {loans.slice(0, 5).map((l) => {
              return (
                <li key={l.id} className="flex items-center justify-between text-sm border-b border-border last:border-0 pb-3 last:pb-0">
                  <div>
                    <div className="font-medium">{l.book_copy?.book?.title}</div>
                    <div className="text-xs text-muted-foreground">{l.member?.name || 'Unknown'} · due {new Date(l.due_date).toLocaleDateString()}</div>
                  </div>
                  <span className={`text-xs px-2 py-1 rounded-full capitalize ${
                    l.status === "overdue" ? "bg-destructive/10 text-destructive" :
                    l.status === "issued" ? "bg-primary/10 text-primary" : "bg-muted text-muted-foreground"
                  }`}>{l.status}</span>
                </li>
              );
            })}
          </ul>
        </div>
        <div className="rounded-xl border border-border bg-card p-6">
          <div className="flex items-center justify-between mb-4">
            <h3 className="font-display text-xl">Quick actions</h3>
            <BarChart3 className="h-4 w-4 text-muted-foreground" />
          </div>
          <div className="grid grid-cols-2 gap-3">
            {[
              { label: "Add new book", to: "/catalog" },
              { label: "Register member", to: "/members" },
              { label: "Process return", to: "/loans" },
              { label: "Issue loan", to: "/loans" },
            ].map((a) => (
              <Link key={a.label} to={a.to} className="rounded-lg border border-border p-4 hover:border-accent hover:bg-accent/5 transition-all duration-200 group">
                <div className="font-medium text-sm">{a.label}</div>
                <ArrowRight className="h-4 w-4 mt-2 text-muted-foreground group-hover:text-accent transition-colors" />
              </Link>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
}
