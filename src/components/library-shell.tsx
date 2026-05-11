import { Link, Outlet, useRouterState } from "@tanstack/react-router";
import { BookOpen, LayoutDashboard, Library, Users, BookMarked, Search, Bell } from "lucide-react";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";

const nav = [
  { to: "/", label: "Dashboard", icon: LayoutDashboard },
  { to: "/catalog", label: "Catalog", icon: Library },
  { to: "/loans", label: "Loans", icon: BookMarked },
  { to: "/members", label: "Members", icon: Users },
];

export function LibraryShell() {
  const path = useRouterState({ select: (s) => s.location.pathname });
  return (
    <div className="min-h-screen flex w-full">
      <aside className="hidden md:flex w-64 flex-col bg-sidebar text-sidebar-foreground border-r border-sidebar-border">
        <div className="px-6 py-6 flex items-center gap-3 border-b border-sidebar-border">
          <div className="h-10 w-10 rounded-md bg-sidebar-primary flex items-center justify-center">
            <BookOpen className="h-5 w-5 text-sidebar-primary-foreground" />
          </div>
          <div>
            <div className="font-display text-xl leading-none">Athenæum</div>
            <div className="text-xs text-sidebar-foreground/60 mt-1">Library System</div>
          </div>
        </div>
        <nav className="flex-1 p-4 space-y-1">
          {nav.map((item) => {
            const active = item.to === "/" ? path === "/" : path.startsWith(item.to);
            return (
              <Link
                key={item.to}
                to={item.to}
                className={`flex items-center gap-3 px-3 py-2.5 rounded-md text-sm transition-colors ${
                  active
                    ? "bg-sidebar-primary text-sidebar-primary-foreground font-medium"
                    : "text-sidebar-foreground/80 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground"
                }`}
              >
                <item.icon className="h-4 w-4" />
                {item.label}
              </Link>
            );
          })}
        </nav>
        <div className="p-4 border-t border-sidebar-border">
          <div className="rounded-lg bg-sidebar-accent p-4">
            <div className="font-display text-sm">Need help?</div>
            <p className="text-xs text-sidebar-foreground/70 mt-1">Visit the librarian's desk or browse our help center.</p>
          </div>
        </div>
      </aside>

      <div className="flex-1 flex flex-col min-w-0">
        <header className="h-16 border-b border-border bg-card/60 backdrop-blur flex items-center gap-4 px-4 md:px-8">
          <div className="flex-1 max-w-xl relative">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
            <Input placeholder="Search titles, authors, ISBN…" className="pl-9 bg-background" />
          </div>
          <Button variant="ghost" size="icon"><Bell className="h-4 w-4" /></Button>
          <div className="h-9 w-9 rounded-full bg-primary text-primary-foreground flex items-center justify-center font-medium text-sm">EM</div>
        </header>
        <main className="flex-1 p-4 md:p-8 max-w-7xl w-full mx-auto">
          <Outlet />
        </main>
      </div>
    </div>
  );
}
