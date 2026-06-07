import { createFileRoute, Link } from "@tanstack/react-router";
import { BookOpen, Mail, RefreshCw } from "lucide-react";
import { useState } from "react";
import { Button } from "@/components/ui/button";
import { useAuth } from "@/lib/auth-context";

export const Route = createFileRoute("/verify-email")({
  head: () => ({ meta: [{ title: "Verify Email — SmartShelf" }] }),
  component: VerifyEmailPage,
});

function VerifyEmailPage() {
  const { user, isAuthenticated } = useAuth();
  const [resent, setResent] = useState(false);
  const [isResending, setIsResending] = useState(false);
  const [error, setError] = useState("");

  const handleResend = async () => {
    setError("");
    setIsResending(true);
    try {
      const token = localStorage.getItem("smartshelf-token");
      const res = await fetch("/api/email/resend", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
          ...(token ? { Authorization: `Bearer ${token}` } : {}),
        },
      });

      if (!res.ok) {
        const body = await res.json().catch(() => ({}));
        throw new Error(
          (body as Record<string, unknown>).message as string || "Failed to resend.",
        );
      }

      setResent(true);
    } catch (err) {
      setError(err instanceof Error ? err.message : "Something went wrong.");
    } finally {
      setIsResending(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center relative overflow-hidden">
      {/* Animated gradient background */}
      <div className="absolute inset-0 bg-gradient-to-br from-primary via-primary/90 to-primary/70" />
      <div className="absolute inset-0 opacity-30 bg-[radial-gradient(ellipse_at_20%_50%,var(--brass),transparent_50%)]" />
      <div className="absolute inset-0 opacity-20 bg-[radial-gradient(ellipse_at_80%_20%,oklch(0.6_0.15_250),transparent_50%)]" />

      <div
        className="absolute inset-0 opacity-[0.04]"
        style={{
          backgroundImage: `linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px)`,
          backgroundSize: "40px 40px",
        }}
      />

      <div className="relative z-10 w-full max-w-md px-4">
        <div className="rounded-2xl border border-white/10 bg-white/[0.07] backdrop-blur-xl shadow-2xl p-8 md:p-10">
          {/* Logo */}
          <div className="flex flex-col items-center mb-6">
            <div className="h-14 w-14 rounded-xl bg-accent/90 flex items-center justify-center mb-4 shadow-lg shadow-accent/20">
              <BookOpen className="h-7 w-7 text-accent-foreground" />
            </div>
            <h1 className="font-display text-3xl text-white tracking-tight">
              Verify Your Email
            </h1>
          </div>

          <div className="text-center space-y-5">
            {/* Email icon */}
            <div className="h-20 w-20 mx-auto rounded-full bg-accent/15 flex items-center justify-center">
              <Mail className="h-9 w-9 text-accent" />
            </div>

            <div>
              <p className="text-sm text-white/70 leading-relaxed">
                We've sent a verification link to{" "}
                {isAuthenticated && user?.email ? (
                  <span className="text-accent font-medium">{user.email}</span>
                ) : (
                  <span className="text-accent font-medium">your email address</span>
                )}
                . Please check your inbox and click the link to verify your account.
              </p>
            </div>

            {error && (
              <div className="rounded-lg bg-destructive/20 border border-destructive/30 px-4 py-3 text-sm text-destructive-foreground">
                {error}
              </div>
            )}

            {resent && (
              <div className="rounded-lg bg-accent/20 border border-accent/30 px-4 py-3 text-sm text-white/80">
                ✓ A new verification link has been sent!
              </div>
            )}

            <div className="space-y-3 pt-2">
              <Button
                onClick={handleResend}
                disabled={isResending}
                className="w-full bg-accent text-accent-foreground hover:bg-accent/90 font-medium py-2.5 shadow-lg shadow-accent/20 transition-all duration-200 hover:shadow-accent/30"
              >
                <RefreshCw
                  className={`h-4 w-4 mr-2 ${isResending ? "animate-spin" : ""}`}
                />
                {isResending ? "Sending…" : "Resend Verification Email"}
              </Button>

              <Link
                to="/login"
                className="block text-xs text-white/40 hover:text-white/60 transition-colors"
              >
                ← Back to sign in
              </Link>
            </div>
          </div>
        </div>

        {/* Footer */}
        <div className="mt-8 text-center">
          <p className="text-xs text-white/30">
            © {new Date().getFullYear()} SmartShelf — All Rights Reserved
          </p>
        </div>
      </div>
    </div>
  );
}
