import { useNavigate } from "@tanstack/react-router";
import { useEffect, type ReactNode } from "react";
import { useAuth } from "@/lib/auth-context";
import { BookOpen } from "lucide-react";

function LoadingSpinner() {
  return (
    <div className="min-h-screen flex items-center justify-center bg-background">
      <div className="flex flex-col items-center gap-4 animate-pulse">
        <div className="h-14 w-14 rounded-xl bg-gradient-to-br from-accent to-accent/70 flex items-center justify-center shadow-lg shadow-accent/20">
          <BookOpen className="h-7 w-7 text-accent-foreground" />
        </div>
        <div className="font-display text-xl text-foreground/70">SmartShelf</div>
      </div>
    </div>
  );
}

export function GuestGuard({ children }: { children: ReactNode }) {
  const { isAuthenticated, isLoading, getDashboardPath } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    if (!isLoading && isAuthenticated) {
      navigate({ to: getDashboardPath() });
    }
  }, [isLoading, isAuthenticated, navigate, getDashboardPath]);

  if (isLoading) return <LoadingSpinner />;
  if (isAuthenticated) return <LoadingSpinner />;

  return <>{children}</>;
}
