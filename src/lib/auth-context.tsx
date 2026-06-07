import {
  createContext,
  useContext,
  useState,
  useEffect,
  useCallback,
  type ReactNode,
} from "react";

// ─── Types ─────────────────────────────────────────────────────────

export interface AuthUser {
  id: number;
  name: string;
  email: string;
  phone?: string;
  status: string;
  email_verified_at: string | null;
  role: {
    id: number;
    name: string;
    description?: string;
    permissions?: { id: number; name: string }[];
  } | null;
  branch_id?: number | null;
}

export interface RegisterData {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
  student_or_employee_id?: string;
  department?: string;
}

interface AuthContextValue {
  user: AuthUser | null;
  isAuthenticated: boolean;
  isLoading: boolean;
  login: (email: string, password: string, remember?: boolean) => Promise<void>;
  register: (data: RegisterData) => Promise<void>;
  logout: () => Promise<void>;
  getDashboardPath: () => string;
}

const AuthContext = createContext<AuthContextValue | undefined>(undefined);

// ─── API helpers ───────────────────────────────────────────────────

const API_BASE = "/api"; // proxied to Laravel backend

const TOKEN_KEY = "smartshelf-token";

function getToken(): string | null {
  if (typeof window === "undefined") return null;
  return localStorage.getItem(TOKEN_KEY);
}

function setToken(token: string) {
  localStorage.setItem(TOKEN_KEY, token);
}

function clearToken() {
  localStorage.removeItem(TOKEN_KEY);
}

async function apiFetch<T = unknown>(
  path: string,
  options: RequestInit = {},
): Promise<T> {
  const token = getToken();
  const headers: Record<string, string> = {
    "Content-Type": "application/json",
    Accept: "application/json",
    ...(options.headers as Record<string, string>),
  };
  if (token) headers["Authorization"] = `Bearer ${token}`;

  const res = await fetch(`${API_BASE}${path}`, {
    ...options,
    headers,
  });

  if (!res.ok) {
    const body = await res.json().catch(() => ({}));
    const message =
      (body as Record<string, unknown>).message ??
      (body as Record<string, unknown>).error ??
      `Request failed (${res.status})`;
    throw new Error(String(message));
  }

  // 204 No Content
  if (res.status === 204) return undefined as T;
  return res.json() as Promise<T>;
}

// ─── Dashboard path mapping ───────────────────────────────────────

const ROLE_DASHBOARD_MAP: Record<string, string> = {
  super_admin: "/",
  branch_admin: "/",
  librarian: "/",
  student_member: "/",
  guest_user: "/",
};

function getDashboardPathForRole(roleName?: string | null): string {
  if (!roleName) return "/";
  return ROLE_DASHBOARD_MAP[roleName] ?? "/";
}

// ─── Provider ─────────────────────────────────────────────────────

export function AuthProvider({ children }: { children: ReactNode }) {
  const [user, setUser] = useState<AuthUser | null>(null);
  const [isLoading, setIsLoading] = useState(true);

  // Hydrate session on mount
  useEffect(() => {
    const token = getToken();
    if (!token) {
      setIsLoading(false);
      return;
    }

    apiFetch<AuthUser>("/user")
      .then((u) => setUser(u))
      .catch(() => {
        clearToken();
        setUser(null);
      })
      .finally(() => setIsLoading(false));
  }, []);

  const login = useCallback(
    async (email: string, password: string, remember = false) => {
      const data = await apiFetch<{ token: string; user: AuthUser }>(
        "/login",
        {
          method: "POST",
          body: JSON.stringify({ email, password, remember }),
        },
      );
      setToken(data.token);
      setUser(data.user);
    },
    [],
  );

  const register = useCallback(async (payload: RegisterData) => {
    const data = await apiFetch<{ token: string; user: AuthUser }>(
      "/register",
      {
        method: "POST",
        body: JSON.stringify(payload),
      },
    );
    setToken(data.token);
    setUser(data.user);
  }, []);

  const logout = useCallback(async () => {
    try {
      await apiFetch("/logout", { method: "POST" });
    } catch {
      // ignore — clear locally regardless
    }
    clearToken();
    setUser(null);
  }, []);

  const getDashboardPath = useCallback(() => {
    return getDashboardPathForRole(user?.role?.name);
  }, [user]);

  return (
    <AuthContext.Provider
      value={{
        user,
        isAuthenticated: !!user,
        isLoading,
        login,
        register,
        logout,
        getDashboardPath,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
}

// ─── Hook ─────────────────────────────────────────────────────────

export function useAuth(): AuthContextValue {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error("useAuth must be used within <AuthProvider>");
  return ctx;
}
