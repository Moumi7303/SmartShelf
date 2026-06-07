import { createFileRoute, Link } from "@tanstack/react-router";
import { BookOpen, Mail, ArrowLeft } from "lucide-react";
import { useState } from "react";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import { GuestGuard } from "@/components/guest-guard";

export const Route = createFileRoute("/forgot-password")({
  head: () => ({ meta: [{ title: "Forgot Password — SmartShelf" }] }),
  component: ForgotPasswordPage,
});

function ForgotPasswordPage() {
  const [email, setEmail] = useState("");
  const [error, setError] = useState("");
  const [success, setSuccess] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError("");

    if (!email.trim()) return setError("Please enter your email address.");

    setIsSubmitting(true);
    try {
      const res = await fetch("/api/forgot-password", {
        method: "POST",
        headers: { "Content-Type": "application/json", Accept: "application/json" },
        body: JSON.stringify({ email }),
      });

      if (!res.ok) {
        const body = await res.json().catch(() => ({}));
        throw new Error(
          (body as Record<string, unknown>).message as string || "Request failed.",
        );
      }

      setSuccess(true);
    } catch (err) {
      setError(err instanceof Error ? err.message : "Something went wrong.");
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <GuestGuard>
      <div className="min-h-screen flex items-center justify-center relative overflow-hidden">
        {/* Animated gradient background */}
        <div className="absolute inset-0 bg-gradient-to-br from-primary via-primary/90 to-primary/70" />
        <div className="absolute inset-0 opacity-30 bg-[radial-gradient(ellipse_at_20%_50%,var(--brass),transparent_50%)]" />
        <div className="absolute inset-0 opacity-20 bg-[radial-gradient(ellipse_at_80%_20%,oklch(0.6_0.15_250),transparent_50%)]" />

        {/* Floating grid pattern */}
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
            <div className="flex flex-col items-center mb-8">
              <div className="h-14 w-14 rounded-xl bg-accent/90 flex items-center justify-center mb-4 shadow-lg shadow-accent/20">
                <BookOpen className="h-7 w-7 text-accent-foreground" />
              </div>
              <h1 className="font-display text-3xl text-white tracking-tight">
                Reset Password
              </h1>
              <p className="text-sm text-white/60 mt-2 text-center leading-relaxed">
                Enter your email and we'll send you a reset link
              </p>
            </div>

            {success ? (
              <div className="text-center space-y-4">
                <div className="h-16 w-16 mx-auto rounded-full bg-accent/20 flex items-center justify-center">
                  <Mail className="h-7 w-7 text-accent" />
                </div>
                <div>
                  <h2 className="font-display text-xl text-white">Check your email</h2>
                  <p className="text-sm text-white/60 mt-2">
                    We've sent a password reset link to{" "}
                    <span className="text-accent font-medium">{email}</span>. Please check
                    your inbox and follow the instructions.
                  </p>
                </div>
                <Link
                  to="/login"
                  className="inline-flex items-center gap-2 text-sm text-accent hover:text-accent/80 font-medium transition-colors"
                >
                  <ArrowLeft className="h-4 w-4" /> Back to sign in
                </Link>
              </div>
            ) : (
              <>
                {/* Error alert */}
                {error && (
                  <div className="mb-5 rounded-lg bg-destructive/20 border border-destructive/30 px-4 py-3 text-sm text-destructive-foreground">
                    {error}
                  </div>
                )}

                <form onSubmit={handleSubmit} className="space-y-5">
                  <div className="space-y-2">
                    <Label
                      htmlFor="forgot-email"
                      className="text-white/80 text-sm font-medium"
                    >
                      Email Address
                    </Label>
                    <div className="relative">
                      <Mail className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-white/40" />
                      <Input
                        id="forgot-email"
                        type="email"
                        placeholder="admin@university.edu"
                        value={email}
                        onChange={(e) => {
                          setEmail(e.target.value);
                          setError("");
                        }}
                        className="pl-10 bg-white/[0.08] border-white/15 text-white placeholder:text-white/30 focus:border-accent focus:ring-accent/30"
                      />
                    </div>
                  </div>

                  <Button
                    type="submit"
                    disabled={isSubmitting}
                    className="w-full bg-accent text-accent-foreground hover:bg-accent/90 font-medium py-2.5 shadow-lg shadow-accent/20 transition-all duration-200 hover:shadow-accent/30"
                  >
                    {isSubmitting ? "Sending…" : "Send Reset Link"}
                  </Button>
                </form>

                <div className="mt-6 text-center">
                  <Link
                    to="/login"
                    className="inline-flex items-center gap-2 text-xs text-white/40 hover:text-white/60 transition-colors"
                  >
                    <ArrowLeft className="h-3 w-3" /> Back to sign in
                  </Link>
                </div>
              </>
            )}
          </div>

          {/* Footer */}
          <div className="mt-8 text-center">
            <p className="text-xs text-white/30">
              © {new Date().getFullYear()} SmartShelf — All Rights Reserved
            </p>
          </div>
        </div>
      </div>
    </GuestGuard>
  );
}
