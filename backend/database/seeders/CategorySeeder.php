<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed default book categories for a university library.
     */
    public function run(): void
    {
        $now = now();

        $categories = [
            ['name' => 'Computer Science', 'description' => 'Programming, algorithms, data structures, AI, and software engineering.'],
            ['name' => 'Mathematics', 'description' => 'Pure and applied mathematics, statistics, and probability.'],
            ['name' => 'Physics', 'description' => 'Classical mechanics, quantum physics, thermodynamics, and optics.'],
            ['name' => 'Chemistry', 'description' => 'Organic, inorganic, physical, and analytical chemistry.'],
            ['name' => 'Biology', 'description' => 'Molecular biology, genetics, ecology, and microbiology.'],
            ['name' => 'Engineering', 'description' => 'Civil, mechanical, electrical, and chemical engineering.'],
            ['name' => 'Medicine', 'description' => 'Clinical medicine, anatomy, pharmacology, and public health.'],
            ['name' => 'Literature', 'description' => 'Fiction, poetry, drama, and literary criticism.'],
            ['name' => 'History', 'description' => 'World history, regional studies, and historical analysis.'],
            ['name' => 'Philosophy', 'description' => 'Ethics, logic, metaphysics, and political philosophy.'],
            ['name' => 'Economics', 'description' => 'Microeconomics, macroeconomics, finance, and econometrics.'],
            ['name' => 'Business & Management', 'description' => 'Marketing, HR, operations, strategy, and entrepreneurship.'],
            ['name' => 'Law', 'description' => 'Constitutional law, criminal law, international law, and jurisprudence.'],
            ['name' => 'Psychology', 'description' => 'Cognitive, behavioral, clinical, and developmental psychology.'],
            ['name' => 'Sociology', 'description' => 'Social theory, research methods, and cultural studies.'],
            ['name' => 'Political Science', 'description' => 'Government systems, international relations, and public policy.'],
            ['name' => 'Art & Design', 'description' => 'Visual arts, graphic design, architecture, and art history.'],
            ['name' => 'Education', 'description' => 'Pedagogy, curriculum development, and educational research.'],
            ['name' => 'Reference', 'description' => 'Encyclopedias, dictionaries, atlases, and general reference works.'],
            ['name' => 'Periodicals', 'description' => 'Academic journals, magazines, and research publications.'],
        ];

        $data = array_map(fn ($cat) => [
            'name' => $cat['name'],
            'slug' => Str::slug($cat['name']),
            'description' => $cat['description'],
            'created_at' => $now,
            'updated_at' => $now,
        ], $categories);

        DB::table('categories')->insert($data);
    }
}
