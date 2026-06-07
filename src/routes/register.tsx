import { createFileRoute, Link } from "@tanstack/react-router";
import { BookOpen, Lock, Mail, Eye, EyeOff } from "lucide-react";
import { useState } from "react";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import { GuestGuard } from "@/components/guest-guard";
import { useAuth } from "@/lib/auth-context";

export const Route = createFileRoute("/register")({
  head: () => ({ meta: [{ title: "Create Account — SmartShelf" }] }),
  component: RegisterPage,
});

const DEPARTMENTS = [
  "Computer Science",
  "Electrical Engineering",
  "Mechanical Engineering",
  "Civil Engineering",
  "Mathematics",
  "Physics",
  "Chemistry",
  "Biology",
  "Business Administration",
  "Economics",
  "English",
  "History",
  "Philosophy",
  "Psychology",
  "Sociology",
  "Political Science",
  "Architecture",
  "Law",
  "Medicine",
  "Pharmacy",
  "Other",
];

function RegisterPage() {
  const { register } = useAuth();
  const [form, setForm] = useState({
    name: "",
    student_or_employee_id: "",
    department: "",
    email: "",
    password: "",
    password_confirmation: "",
  });
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirm, setShowConfirm] = useState(false);
  const [error, setError] = useState("");
  const [isSubmitting, setIsSubmitting] = useState(false);

  const updateField = (field: string, value: string) => {
    setForm((prev) => ({ ...prev, [field]: value }));
    setError("");
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError("");

    if (!form.name.trim()) return setError("Full name is required.");
    if (!form.email.trim()) return setError("Email is required.");
    if (form.password.length < 8)
      return setError("Password must be at least 8 characters.");
    if (form.password !== form.password_confirmation)
      return setError("Passwords do not match.");

    setIsSubmitting(true);
    try {
      await register(form);
    } catch (err) {
      setError(err instanceof Error ? err.message : "Registration failed.");
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <GuestGuard>
      <div className="min-h-screen flex items-center justify-center relative overflow-hidden py-8">
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
          {/* Registration card with glassmorphism */}
          <div className="rounded-2xl border border-white/10 bg-white/[0.07] backdrop-blur-xl shadow-2xl p-8 md:p-10">
            {/* Logo */}
            <div className="flex flex-col items-center mb-6">
              <div className="h-14 w-14 rounded-xl bg-accent/90 flex items-center justify-center mb-4 shadow-lg shadow-accent/20">
                <BookOpen className="h-7 w-7 text-accent-foreground" />
              </div>
              <h1 className="font-display text-3xl text-white tracking-tight">
                Create SmartShelf Account
              </h1>
              <p className="text-sm text-white/60 mt-2 text-center leading-relaxed">
                Register to access library resources
              </p>
            </div>

            {/* Error alert */}
            {error && (
              <div className="mb-5 rounded-lg bg-destructive/20 border border-destructive/30 px-4 py-3 text-sm text-destructive-foreground">
                {error}
              </div>
            )}

            {/* Form */}
            <form onSubmit={handleSubmit} className="space-y-4">
              {/* Full Name */}
              <div className="space-y-1.5">
                <Label htmlFor="reg-name" className="text-white/80 text-sm font-medium">
                  Full Name
                </Label>
                <Input
                  id="reg-name"
                  type="text"
                  placeholder="Your full name"
                  value={form.name}
                  onChange={(e) => updateField("name", e.target.value)}
                  className="bg-white/[0.08] border-white/15 text-white placeholder:text-white/30 focus:border-accent focus:ring-accent/30"
                />
              </div>

              {/* Two-column row: Student ID + Department */}
              <div className="grid grid-cols-2 gap-3">
                <div className="space-y-1.5">
                  <Label htmlFor="reg-sid" className="text-white/80 text-sm font-medium">
                    Student / Employee ID
                  </Label>
                  <Input
                    id="reg-sid"
                    type="text"
                    placeholder="e.g. STU-2024001"
                    value={form.student_or_employee_id}
                    onChange={(e) =>
                      updateField("student_or_employee_id", e.target.value)
                    }
                    className="bg-white/[0.08] border-white/15 text-white placeholder:text-white/30 focus:border-accent focus:ring-accent/30"
                  />
                </div>
                <div className="space-y-1.5">
                  <Label htmlFor="reg-dept" className="text-white/80 text-sm font-medium">
                    Department
                  </Label>
                  <select
                    id="reg-dept"
                    value={form.department}
                    onChange={(e) => updateField("department", e.target.value)}
                    className="flex h-9 w-full rounded-md border bg-white/[0.08] border-white/15 text-white px-3 py-1 text-sm focus:border-accent focus:ring-accent/30 focus:outline-none [&>option]:bg-[oklch(0.20_0.03_230)] [&>option]:text-white"
                  >
                    <option value="">Select…</option>
                    {DEPARTMENTS.map((d) => (
                      <option key={d} value={d}>
                        {d}
                      </option>
                    ))}
                  </select>
                </div>
              </div>

              {/* Email */}
              <div className="space-y-1.5">
                <Label htmlFor="reg-email" className="text-white/80 text-sm font-medium">
                  Email Address
                </Label>
                <div className="relative">
                  <Mail className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-white/40" />
                  <Input
                    id="reg-email"
                    type="email"
                    placeholder="you@university.edu"
                    value={form.email}
                    onChange={(e) => updateField("email", e.target.value)}
                    className="pl-10 bg-white/[0.08] border-white/15 text-white placeholder:text-white/30 focus:border-accent focus:ring-accent/30"
                  />
                </div>
              </div>

              {/* Password */}
              <div className="space-y-1.5">
                <Label htmlFor="reg-password" className="text-white/80 text-sm font-medium">
                  Password
                </Label>
                <div className="relative">
                  <Lock className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-white/40" />
                  <Input
                    id="reg-password"
                    type={showPassword ? "text" : "password"}
                    placeholder="••••••••"
                    value={form.password}
                    onChange={(e) => updateField("password", e.target.value)}
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

              {/* Confirm Password */}
              <div className="space-y-1.5">
                <Label
                  htmlFor="reg-confirm"
                  className="text-white/80 text-sm font-medium"
                >
                  Confirm Password
                </Label>
                <div className="relative">
                  <Lock className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-white/40" />
                  <Input
                    id="reg-confirm"
                    type={showConfirm ? "text" : "password"}
                    placeholder="••••••••"
                    value={form.password_confirmation}
                    onChange={(e) =>
                      updateField("password_confirmation", e.target.value)
                    }
                    className="pl-10 pr-10 bg-white/[0.08] border-white/15 text-white placeholder:text-white/30 focus:border-accent focus:ring-accent/30"
                  />
                  <button
                    type="button"
                    onClick={() => setShowConfirm(!showConfirm)}
                    className="absolute right-3 top-1/2 -translate-y-1/2 text-white/40 hover:text-white/70 transition-colors"
                    tabIndex={-1}
                  >
                    {showConfirm ? (
                      <EyeOff className="h-4 w-4" />
                    ) : (
                      <Eye className="h-4 w-4" />
                    )}
                  </button>
                </div>
              </div>

              <Button
                type="submit"
                disabled={isSubmitting}
                className="w-full bg-accent text-accent-foreground hover:bg-accent/90 font-medium py-2.5 shadow-lg shadow-accent/20 transition-all duration-200 hover:shadow-accent/30 mt-2"
              >
                {isSubmitting ? "Creating account…" : "Create Account"}
              </Button>
            </form>

            <div className="mt-6 text-center">
              <p className="text-xs text-white/40">
                Already have an account?{" "}
                <Link
                  to="/login"
                  className="text-accent hover:text-accent/80 font-medium transition-colors"
                >
                  Sign in
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
