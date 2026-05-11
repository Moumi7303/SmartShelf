import { createFileRoute, Link } from "@tanstack/react-router";
import { BookOpen, Users, BookMarked, TrendingUp, ArrowRight, Clock } from "lucide-react";
import { books, loans, members } from "@/lib/library-data";
import { BookCard } from "@/components/book-card";

export const Route = createFileRoute("/")({
  head: () => ({ meta: [{ title: "Dashboard — Inside Love" }] }),
  component: Dashboard,
});

function Stat({ icon: Icon, label, value, hint }: any) {
  return (
    <div className="rounded-xl border border-border bg-card p-5">
      <div className="flex items-center justify-between">
        <span className="text-xs uppercase tracking-wider text-muted-foreground">{label}</span>
        <Icon className="h-4 w-4 text-accent" />
      </div>
      <div className="mt-3 font-display text-3xl">{value}</div>
      <div className="mt-1 text-xs text-muted-foreground">{hint}</div>
    </div>
  );
}

function Dashboard() {
  const overdue = loans.filter((l) => l.status === "overdue");
  const active = loans.filter((l) => l.status === "active");
  const featured = books.slice(0, 4);

  return (
    <div className="space-y-10">
      <section className="relative overflow-hidden rounded-2xl border border-border bg-gradient-to-br from-primary via-primary to-primary/80 text-primary-foreground p-8 md:p-12">
        <div className="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_80%_20%,var(--brass),transparent_50%)]" />
        <div className="relative max-w-2xl">
          <div className="text-xs uppercase tracking-[0.2em] text-accent">Welcome back, Eleanor</div>
          <h1 className="mt-3 font-display text-4xl md:text-5xl leading-tight">A quiet morning at the library.</h1>
          <p className="mt-3 text-primary-foreground/80">Three new returns overnight, and two reservations need attention.</p>
          <div className="mt-6 flex gap-3">
            <Link to="/catalog" className="inline-flex items-center gap-2 rounded-md bg-accent px-4 py-2.5 text-sm font-medium text-accent-foreground hover:opacity-90">
              Browse catalog <ArrowRight className="h-4 w-4" />
            </Link>
            <Link to="/loans" className="inline-flex items-center gap-2 rounded-md border border-primary-foreground/20 px-4 py-2.5 text-sm font-medium hover:bg-primary-foreground/10">
              Manage loans
            </Link>
          </div>
        </div>
      </section>

      <section className="grid gap-4 grid-cols-2 lg:grid-cols-4">
        <Stat icon={BookOpen} label="Total Books" value={books.reduce((a, b) => a + b.total, 0)} hint={`${books.length} unique titles`} />
        <Stat icon={BookMarked} label="Active Loans" value={active.length} hint="Due within 14 days" />
        <Stat icon={Clock} label="Overdue" value={overdue.length} hint="Needs follow-up" />
        <Stat icon={Users} label="Members" value={members.length} hint="+2 this month" />
      </section>

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

      <section className="grid gap-6 md:grid-cols-2">
        <div className="rounded-xl border border-border bg-card p-6">
          <div className="flex items-center justify-between mb-4">
            <h3 className="font-display text-xl">Recent loans</h3>
            <TrendingUp className="h-4 w-4 text-muted-foreground" />
          </div>
          <ul className="space-y-3">
            {loans.slice(0, 5).map((l) => {
              const book = books.find((b) => b.id === l.bookId);
              return (
                <li key={l.id} className="flex items-center justify-between text-sm border-b border-border last:border-0 pb-3 last:pb-0">
                  <div>
                    <div className="font-medium">{book?.title}</div>
                    <div className="text-xs text-muted-foreground">{l.member} · due {l.due}</div>
                  </div>
                  <span className={`text-xs px-2 py-1 rounded-full ${
                    l.status === "overdue" ? "bg-destructive/10 text-destructive" :
                    l.status === "active" ? "bg-primary/10 text-primary" : "bg-muted text-muted-foreground"
                  }`}>{l.status}</span>
                </li>
              );
            })}
          </ul>
        </div>
        <div className="rounded-xl border border-border bg-card p-6">
          <h3 className="font-display text-xl mb-4">Quick actions</h3>
          <div className="grid grid-cols-2 gap-3">
            {[
              { label: "Add new book", to: "/catalog" },
              { label: "Register member", to: "/members" },
              { label: "Process return", to: "/loans" },
              { label: "Issue loan", to: "/loans" },
            ].map((a) => (
              <Link key={a.label} to={a.to} className="rounded-lg border border-border p-4 hover:border-accent hover:bg-accent/5 transition-colors">
                <div className="font-medium text-sm">{a.label}</div>
                <ArrowRight className="h-4 w-4 mt-2 text-muted-foreground" />
              </Link>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
}
