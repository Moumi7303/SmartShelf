import { createFileRoute, Link } from "@tanstack/react-router";
import { BookOpen, Lock, Mail, Eye, EyeOff } from "lucide-react";
import { useState } from "react";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import { GuestGuard } from "@/components/guest-guard";
import { useAuth } from "@/lib/auth-context";

export const Route = createFileRoute("/login")({
  head: () => ({ meta: [{ title: "Sign In — SmartShelf" }] }),
  component: LoginPage,
});

function LoginPage() {
  const { login } = useAuth();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [rememberMe, setRememberMe] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const [error, setError] = useState("");
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError("");

    if (!email.trim()) return setError("Please enter your email.");
    if (!password) return setError("Please enter your password.");

    setIsSubmitting(true);
    try {
      await login(email, password, rememberMe);
    } catch (err) {
      setError(
        err instanceof Error ? err.message : "Invalid credentials. Please try again.",
      );
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
          {/* Login card with glassmorphism */}
          <div className="rounded-2xl border border-white/10 bg-white/[0.07] backdrop-blur-xl shadow-2xl p-8 md:p-10">
            {/* Logo */}
            <div className="flex flex-col items-center mb-8">
              <div className="h-14 w-14 rounded-xl bg-accent/90 flex items-center justify-center mb-4 shadow-lg shadow-accent/20">
                <BookOpen className="h-7 w-7 text-accent-foreground" />
              </div>
              <h1 className="font-display text-3xl text-white tracking-tight">
                Welcome to SmartShelf
              </h1>
              <p className="text-sm text-white/60 mt-2 text-center leading-relaxed">
                Smart Multi-Branch University Library Management System
              </p>
            </div>

            {/* Error alert */}
            {error && (
              <div className="mb-5 rounded-lg bg-destructive/20 border border-destructive/30 px-4 py-3 text-sm text-destructive-foreground">
                {error}
              </div>
            )}

            {/* Form */}
            <form onSubmit={handleSubmit} className="space-y-5">
              <div className="space-y-2">
                <Label htmlFor="login-email" className="text-white/80 text-sm font-medium">
                  Email Address
                </Label>
                <div className="relative">
                  <Mail className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-white/40" />
                  <Input
                    id="login-email"
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

              <div className="space-y-2">
                <div className="flex items-center justify-between">
                  <Label
                    htmlFor="login-password"
                    className="text-white/80 text-sm font-medium"
                  >
                    Password
                  </Label>
                  <Link
                    to="/forgot-password"
                    className="text-xs text-accent hover:text-accent/80 transition-colors"
                  >
                    Forgot password?
                  </Link>
                </div>
                <div className="relative">
                  <Lock className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-white/40" />
                  <Input
                    id="login-password"
                    type={showPassword ? "text" : "password"}
                    placeholder="••••••••"
                    value={password}
                    onChange={(e) => {
                      setPassword(e.target.value);
                      setError("");
                    }}
                    className="pl-10 pr-10 bg-white/[0.08] border-white/15 text-white placeholder:text-white/30 focus:border-accent focus:ring-accent/30"
                  />
                  <button
                    type="button"
                    onClick={() => setShowPassword(!showPassword)}
                    className="absolute right-3 top-1/2 -translate-y-1/2 text-white/40 hover:text-white/70 transition-colors"
                    tabIndex={-1}
                  >
                    {showPassword ? (
                      <EyeOff className="h-4 w-4" />
                    ) : (
                      <Eye className="h-4 w-4" />
                    )}
                  </button>
                </div>
              </div>

              {/* Remember me */}
              <div className="flex items-center gap-2">
                <input
                  id="login-remember"
                  type="checkbox"
                  checked={rememberMe}
                  onChange={(e) => setRememberMe(e.target.checked)}
                  className="h-4 w-4 rounded border-white/20 bg-white/[0.08] text-accent focus:ring-accent/30 accent-[var(--accent)]"
                />
                <label
                  htmlFor="login-remember"
                  className="text-sm text-white/60 cursor-pointer select-none"
                >
                  Remember me
                </label>
              </div>

              <Button
                type="submit"
                disabled={isSubmitting}
                className="w-full bg-accent text-accent-foreground hover:bg-accent/90 font-medium py-2.5 shadow-lg shadow-accent/20 transition-all duration-200 hover:shadow-accent/30"
              >
                {isSubmitting ? "Signing in…" : "Sign in to SmartShelf"}
              </Button>
            </form>

            <div className="mt-6 text-center">
              <p className="text-xs text-white/40">
                Don't have an account?{" "}
                <Link
                  to="/register"
                  className="text-accent hover:text-accent/80 font-medium transition-colors"
                >
                  Create an account
                </Link>
              </p>
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
    </GuestGuard>
  );
}
