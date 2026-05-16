import { Link, Outlet, useRouterState } from "@tanstack/react-router";
import {
  BookOpen,
  LayoutDashboard,
  Library,
  Users,
  BookMarked,
  Search,
  Bell,
  Sun,
  Moon,
  Menu,
  X,
  LogIn,
} from "lucide-react";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { useTheme } from "@/components/theme-provider";
import { useState } from "react";

const nav = [
  { to: "/", label: "Dashboard", icon: LayoutDashboard },
  { to: "/catalog", label: "Catalog", icon: Library },
  { to: "/loans", label: "Loans", icon: BookMarked },
  { to: "/members", label: "Members", icon: Users },
];

function SmartShelfLogo({ collapsed = false }: { collapsed?: boolean }) {
  return (
    <div className="flex items-center gap-3">
      <div className="h-10 w-10 rounded-lg bg-gradient-to-br from-accent to-accent/70 flex items-center justify-center shadow-lg shadow-accent/20 shrink-0">
        <svg
          width="22"
          height="22"
          viewBox="0 0 24 24"
          fill="none"
          className="text-accent-foreground"
        >
          {/* Book spine */}
          <path
            d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5A2.5 2.5 0 0 1 4 19.5Z"
            stroke="currentColor"
            strokeWidth="1.5"
            strokeLinejoin="round"
          />
          {/* Digital grid dots */}
          <circle cx="9" cy="8" r="1" fill="currentColor" opacity="0.7" />
          <circle cx="13" cy="8" r="1" fill="currentColor" opacity="0.7" />
          <circle cx="9" cy="12" r="1" fill="currentColor" opacity="0.5" />
          <circle cx="13" cy="12" r="1" fill="currentColor" opacity="0.5" />
          <circle cx="17" cy="8" r="1" fill="currentColor" opacity="0.3" />
          <circle cx="17" cy="12" r="1" fill="currentColor" opacity="0.3" />
          {/* Bottom binding */}
          <path
            d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"
            stroke="currentColor"
            strokeWidth="1.5"
            strokeLinejoin="round"
          />
        </svg>
      </div>
      {!collapsed && (
        <div className="min-w-0">
          <div className="font-display text-xl leading-none tracking-tight">SmartShelf</div>
          <div className="text-[10px] uppercase tracking-[0.15em] text-sidebar-foreground/50 mt-1 font-medium">
            University Library
          </div>
        </div>
      )}
    </div>
  );
}

export function LibraryShell() {
  const path = useRouterState({ select: (s) => s.location.pathname });
  const { resolvedTheme, setTheme } = useTheme();
  const [mobileOpen, setMobileOpen] = useState(false);

  const toggleTheme = () => setTheme(resolvedTheme === "dark" ? "light" : "dark");

  return (
    <div className="min-h-screen flex w-full">
      {/* Desktop sidebar */}
      <aside className="hidden md:flex w-64 flex-col bg-sidebar text-sidebar-foreground border-r border-sidebar-border shrink-0">
        <div className="px-6 py-5 border-b border-sidebar-border">
          <SmartShelfLogo />
        </div>
        <nav className="flex-1 p-4 space-y-1">
          {nav.map((item) => {
            const active = item.to === "/" ? path === "/" : path.startsWith(item.to);
            return (
              <Link
                key={item.to}
                to={item.to}
                className={`flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 ${
                  active
                    ? "bg-sidebar-primary text-sidebar-primary-foreground font-medium shadow-sm shadow-sidebar-primary/20"
                    : "text-sidebar-foreground/70 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground"
                }`}
              >
                <item.icon className="h-4 w-4" />
                {item.label}
              </Link>
            );
          })}
        </nav>

        {/* Sidebar bottom */}
        <div className="p-4 space-y-3">
          <Link
            to="/login"
            className="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-sidebar-foreground/70 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground transition-all duration-200"
          >
            <LogIn className="h-4 w-4" />
            Sign In
          </Link>
          <div className="rounded-lg bg-sidebar-accent/60 p-4">
            <div className="font-display text-sm">Need help?</div>
            <p className="text-xs text-sidebar-foreground/60 mt-1">
              Visit the librarian's desk or browse our help center.
            </p>
          </div>
          <div className="px-1 pt-2 border-t border-sidebar-border">
            <p className="text-[10px] text-sidebar-foreground/30 text-center">
              © {new Date().getFullYear()} SmartShelf — All Rights Reserved
            </p>
          </div>
        </div>
      </aside>

      {/* Mobile sidebar overlay */}
      {mobileOpen && (
        <div className="md:hidden fixed inset-0 z-50 flex">
          <div
            className="absolute inset-0 bg-black/50 backdrop-blur-sm"
            onClick={() => setMobileOpen(false)}
          />
          <aside className="relative w-72 flex flex-col bg-sidebar text-sidebar-foreground shadow-2xl animate-in slide-in-from-left duration-300">
            <div className="px-6 py-5 border-b border-sidebar-border flex items-center justify-between">
              <SmartShelfLogo />
              <button onClick={() => setMobileOpen(false)} className="text-sidebar-foreground/60 hover:text-sidebar-foreground">
                <X className="h-5 w-5" />
              </button>
            </div>
            <nav className="flex-1 p-4 space-y-1">
              {nav.map((item) => {
                const active = item.to === "/" ? path === "/" : path.startsWith(item.to);
                return (
                  <Link
                    key={item.to}
                    to={item.to}
                    onClick={() => setMobileOpen(false)}
                    className={`flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 ${
                      active
                        ? "bg-sidebar-primary text-sidebar-primary-foreground font-medium"
                        : "text-sidebar-foreground/70 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground"
                    }`}
                  >
                    <item.icon className="h-4 w-4" />
                    {item.label}
                  </Link>
                );
              })}
            </nav>
            <div className="p-4 border-t border-sidebar-border">
              <p className="text-[10px] text-sidebar-foreground/30 text-center">
                © {new Date().getFullYear()} SmartShelf — All Rights Reserved
              </p>
            </div>
          </aside>
        </div>
      )}

      {/* Main content area */}
      <div className="flex-1 flex flex-col min-w-0">
        {/* Top bar with gradient accent */}
        <header className="h-16 border-b border-border bg-card/70 backdrop-blur-md flex items-center gap-4 px-4 md:px-8 relative">
          {/* Gradient top accent line */}
          <div className="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-primary via-accent to-primary/60" />

          {/* Mobile menu button */}
          <button
            className="md:hidden text-muted-foreground hover:text-foreground transition-colors"
            onClick={() => setMobileOpen(true)}
          >
            <Menu className="h-5 w-5" />
          </button>

          {/* Mobile logo */}
          <div className="md:hidden">
            <span className="font-display text-lg">SmartShelf</span>
          </div>

          {/* Search */}
          <div className="flex-1 max-w-xl relative hidden md:block">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
            <Input placeholder="Search titles, authors, ISBN…" className="pl-9 bg-background/50" />
          </div>

          <div className="ml-auto flex items-center gap-2">
            {/* Theme toggle */}
            <Button variant="ghost" size="icon" onClick={toggleTheme} className="text-muted-foreground hover:text-foreground">
              {resolvedTheme === "dark" ? <Sun className="h-4 w-4" /> : <Moon className="h-4 w-4" />}
            </Button>
            <Button variant="ghost" size="icon" className="text-muted-foreground hover:text-foreground">
              <Bell className="h-4 w-4" />
            </Button>
            <div className="h-9 w-9 rounded-full bg-gradient-to-br from-primary to-accent text-primary-foreground flex items-center justify-center font-medium text-sm shadow-sm">
              SA
            </div>
          </div>
        </header>

        <main className="flex-1 p-4 md:p-8 max-w-7xl w-full mx-auto">
          <Outlet />
        </main>
      </div>
    </div>
  );
}
