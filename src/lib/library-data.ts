export type Book = {
  id: string;
  title: string;
  author: string;
  genre: string;
  year: number;
  rating: number;
  available: number;
  total: number;
  cover: string; // gradient class
  description: string;
};

export const books: Book[] = [
  { id: "1", title: "The Midnight Library", author: "Matt Haig", genre: "Fiction", year: 2020, rating: 4.3, available: 3, total: 5, cover: "from-emerald-700 to-emerald-950", description: "Between life and death is a library, and within that library, the shelves go on forever." },
  { id: "2", title: "Project Hail Mary", author: "Andy Weir", genre: "Sci-Fi", year: 2021, rating: 4.7, available: 1, total: 4, cover: "from-amber-600 to-rose-900", description: "A lone astronaut must save humanity from extinction." },
  { id: "3", title: "Klara and the Sun", author: "Kazuo Ishiguro", genre: "Fiction", year: 2021, rating: 4.0, available: 2, total: 3, cover: "from-sky-500 to-indigo-900", description: "From the Nobel laureate, a thrilling tale of love and what it means to be human." },
  { id: "4", title: "Atomic Habits", author: "James Clear", genre: "Self-Help", year: 2018, rating: 4.8, available: 0, total: 6, cover: "from-stone-700 to-stone-950", description: "Tiny changes, remarkable results." },
  { id: "5", title: "Educated", author: "Tara Westover", genre: "Memoir", year: 2018, rating: 4.6, available: 4, total: 5, cover: "from-rose-700 to-rose-950", description: "A memoir about the struggle for self-invention." },
  { id: "6", title: "The Song of Achilles", author: "Madeline Miller", genre: "Historical", year: 2011, rating: 4.5, available: 2, total: 4, cover: "from-yellow-600 to-orange-900", description: "A tale of gods, kings, immortal fame and the human heart." },
  { id: "7", title: "Dune", author: "Frank Herbert", genre: "Sci-Fi", year: 1965, rating: 4.6, available: 1, total: 3, cover: "from-orange-700 to-amber-950", description: "The epic saga of a desert planet called Arrakis." },
  { id: "8", title: "Pachinko", author: "Min Jin Lee", genre: "Historical", year: 2017, rating: 4.4, available: 3, total: 3, cover: "from-pink-700 to-fuchsia-950", description: "A sweeping saga of a Korean family in Japan." },
  { id: "9", title: "The Name of the Wind", author: "Patrick Rothfuss", genre: "Fantasy", year: 2007, rating: 4.7, available: 2, total: 5, cover: "from-teal-700 to-emerald-950", description: "The riveting first-person narrative of Kvothe." },
  { id: "10", title: "Circe", author: "Madeline Miller", genre: "Fantasy", year: 2018, rating: 4.5, available: 1, total: 3, cover: "from-violet-700 to-indigo-950", description: "The story of the banished witch daughter of Helios." },
  { id: "11", title: "Sapiens", author: "Yuval Noah Harari", genre: "Non-Fiction", year: 2011, rating: 4.5, available: 0, total: 4, cover: "from-yellow-700 to-stone-900", description: "A brief history of humankind." },
  { id: "12", title: "Where the Crawdads Sing", author: "Delia Owens", genre: "Fiction", year: 2018, rating: 4.6, available: 2, total: 4, cover: "from-lime-700 to-green-950", description: "A painfully beautiful coming-of-age mystery." },
];

export const genres = ["All", "Fiction", "Sci-Fi", "Fantasy", "Self-Help", "Memoir", "Historical", "Non-Fiction"];

export type Loan = {
  id: string;
  bookId: string;
  member: string;
  borrowed: string;
  due: string;
  status: "active" | "overdue" | "returned";
};

export const loans: Loan[] = [
  { id: "L-1043", bookId: "2", member: "Amelia Chen", borrowed: "2026-04-28", due: "2026-05-12", status: "active" },
  { id: "L-1042", bookId: "4", member: "Noah Patel", borrowed: "2026-04-15", due: "2026-04-29", status: "overdue" },
  { id: "L-1041", bookId: "7", member: "Sofia Rivera", borrowed: "2026-05-02", due: "2026-05-16", status: "active" },
  { id: "L-1040", bookId: "11", member: "Liam Okafor", borrowed: "2026-04-10", due: "2026-04-24", status: "overdue" },
  { id: "L-1039", bookId: "1", member: "Hana Yamada", borrowed: "2026-05-05", due: "2026-05-19", status: "active" },
  { id: "L-1038", bookId: "9", member: "Marcus Lee", borrowed: "2026-04-20", due: "2026-05-04", status: "returned" },
  { id: "L-1037", bookId: "3", member: "Zara Ahmed", borrowed: "2026-05-01", due: "2026-05-15", status: "active" },
];

export const members = [
  { id: "M-001", name: "Amelia Chen", email: "amelia@library.io", joined: "2024-03-12", active: 2 },
  { id: "M-002", name: "Noah Patel", email: "noah@library.io", joined: "2023-11-04", active: 1 },
  { id: "M-003", name: "Sofia Rivera", email: "sofia@library.io", joined: "2025-01-20", active: 3 },
  { id: "M-004", name: "Liam Okafor", email: "liam@library.io", joined: "2022-08-18", active: 1 },
  { id: "M-005", name: "Hana Yamada", email: "hana@library.io", joined: "2024-06-30", active: 2 },
  { id: "M-006", name: "Marcus Lee", email: "marcus@library.io", joined: "2025-09-09", active: 0 },
  { id: "M-007", name: "Zara Ahmed", email: "zara@library.io", joined: "2023-02-14", active: 1 },
];
