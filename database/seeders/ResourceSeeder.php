<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResourceSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        // 1. Ensure 'Knowledge Center Category' exists in tbl_master_type
        $masterType = DB::table('tbl_master_type')->where('name', 'Knowledge Center Category')->first();
        if (!$masterType) {
            $masterTypeId = DB::table('tbl_master_type')->insertGetId([
                'name' => 'Knowledge Center Category',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            $masterTypeId = $masterType->id;
        }

        // 2. Insert master parent categories matching Next.js RESOURCE_CATEGORY_MAP
        // 239 -> Blog, 240 -> Webinar, 241 -> Article, 242 -> Info
        $parentCategories = [
            [
                'id' => 239,
                'master_type_id' => $masterTypeId,
                'name' => 'Blog',
                'code' => 'BLOG',
                'description' => 'Blog articles and posts',
                'main_account' => null,
                'employee_id' => null,
                'status' => 'Active',
                'company_id' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 240,
                'master_type_id' => $masterTypeId,
                'name' => 'Webinar',
                'code' => 'WEBINAR',
                'description' => 'Webinar recordings and materials',
                'main_account' => null,
                'employee_id' => null,
                'status' => 'Active',
                'company_id' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 241,
                'master_type_id' => $masterTypeId,
                'name' => 'Article',
                'code' => 'ARTICLE',
                'description' => 'In-depth industry articles and technical guides',
                'main_account' => null,
                'employee_id' => null,
                'status' => 'Active',
                'company_id' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 242,
                'master_type_id' => $masterTypeId,
                'name' => 'Info',
                'code' => 'INFO',
                'description' => 'Course information and certification overviews',
                'main_account' => null,
                'employee_id' => null,
                'status' => 'Active',
                'company_id' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($parentCategories as $parent) {
            DB::table('tbl_master_parent')->updateOrInsert(['id' => $parent['id']], $parent);
        }

        // 3. Populate Articles for various course categories
        // Top active course categories:
        // 5: Agile
        // 8: IT Service Management
        // 11: Project Management
        // 18: IT Security and Governance
        // 34: DevOps
        // 50: SAFe Scaled Agile Framework
        // 58: Program Management
        // 2: Data Structures & Algorithms / IT-Sub Category
        $articles = [
            [
                'course_category_id' => 5, // Agile
                'course_id' => '25',
                'url_title' => 'agile-frameworks-and-scrum-best-practices-guide',
                'resource_category' => 241, // Article
                'resource_title' => 'Complete Guide to Agile Frameworks & Scrum Best Practices in 2026',
                'header_image' => 'assets/landingpage/blog1_image.png',
                'resource_auther' => 'ScholarAcad Expert Team',
                'short_description' => 'Master the core principles of Agile and Scrum methodologies. Learn how sprint planning, daily standups, and retrospectives drive project success.',
                'overview' => '<h2>Introduction to Agile</h2><p>Agile is an iterative approach to project management and software development that helps teams deliver value to their customers faster and with fewer headaches.</p><h2>The Scrum Framework</h2><p>Scrum is one of the most widely used Agile frameworks, emphasizing teamwork, accountability, and iterative progress toward well-defined goals.</p>',
                'meta_description' => 'Complete Guide to Agile Frameworks and Scrum Best Practices for modern project managers and engineering teams.',
                'meta_keywords' => 'Agile, Scrum, Sprint Planning, Agile Frameworks, Certification',
                'page_title' => 'Agile Frameworks & Scrum Best Practices Guide | ScholarAcad',
                'status' => 'Active',
                'company_id' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'course_category_id' => 11, // Project Management
                'course_id' => '23',
                'url_title' => 'pmp-certification-roadmap-and-study-strategies',
                'resource_category' => 241, // Article
                'resource_title' => 'PMP Certification Roadmap: Step-by-Step Study Strategies & Tips',
                'header_image' => 'assets/landingpage/blog2_image.png',
                'resource_auther' => 'PMP Certified Trainer',
                'short_description' => 'Everything you need to know about preparing for and clearing the PMP certification exam on your first attempt with confidence.',
                'overview' => '<h2>Understanding the PMP Exam Structure</h2><p>The PMP exam covers three main domains: People, Process, and Business Environment. Mastering all three requires structured preparation and mock testing.</p>',
                'meta_description' => 'Step-by-step PMP certification preparation guide and study strategies.',
                'meta_keywords' => 'PMP, Project Management, PMI, Exam Tips, Certification',
                'page_title' => 'PMP Certification Roadmap & Tips | ScholarAcad',
                'status' => 'Active',
                'company_id' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'course_category_id' => 34, // DevOps
                'course_id' => '34',
                'url_title' => 'devops-cicd-pipeline-implementation-guide',
                'resource_category' => 241, // Article
                'resource_title' => 'Building Scalable CI/CD Pipelines with DevOps Best Practices',
                'header_image' => 'assets/landingpage/blog1_image.png',
                'resource_auther' => 'DevOps Architect',
                'short_description' => 'A practical walkthrough of continuous integration and continuous deployment pipelines, automated testing, containerization, and monitoring.',
                'overview' => '<h2>CI/CD Foundations</h2><p>Continuous Integration and Continuous Delivery are crucial for delivering secure, resilient software at scale.</p>',
                'meta_description' => 'Comprehensive guide to building scalable CI/CD pipelines with DevOps best practices.',
                'meta_keywords' => 'DevOps, CI/CD, Docker, Kubernetes, Jenkins, GitHub Actions',
                'page_title' => 'Building Scalable CI/CD Pipelines | ScholarAcad',
                'status' => 'Active',
                'company_id' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'course_category_id' => 8, // IT Service Management
                'course_id' => '11',
                'url_title' => 'itil-4-foundation-core-concepts-and-guiding-principles',
                'resource_category' => 241, // Article
                'resource_title' => 'ITIL 4 Foundation: The 7 Guiding Principles Explained',
                'header_image' => 'assets/landingpage/blog2_image.png',
                'resource_auther' => 'ITSM Lead Consultant',
                'short_description' => 'Deep dive into ITIL 4 Service Value System, Service Value Chain, and the 7 guiding principles for optimal IT service management.',
                'overview' => '<h2>The 7 Guiding Principles of ITIL 4</h2><p>Focus on value, start where you are, progress iteratively with feedback, collaborate and promote visibility, think and work holistically, keep it simple and practical, optimize and automate.</p>',
                'meta_description' => 'Explore ITIL 4 Foundation core concepts and the 7 guiding principles with practical examples.',
                'meta_keywords' => 'ITIL 4, ITSM, IT Service Management, Guiding Principles',
                'page_title' => 'ITIL 4 Foundation Guiding Principles | ScholarAcad',
                'status' => 'Active',
                'company_id' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'course_category_id' => 18, // IT Security and Governance
                'course_id' => '19',
                'url_title' => 'cybersecurity-governance-and-cobit-framework-alignment',
                'resource_category' => 241, // Article
                'resource_title' => 'Cybersecurity Governance: Aligning COBIT & ISO 27001 for Enterprise Protection',
                'header_image' => 'assets/landingpage/blog1_image.png',
                'resource_auther' => 'CISO Advisor',
                'short_description' => 'How leading enterprises structure their IT governance, threat mitigation, and compliance frameworks to secure digital assets effectively.',
                'overview' => '<h2>Enterprise Security Governance</h2><p>Modern cybersecurity governance bridges executive leadership and technical execution to safeguard mission-critical data.</p>',
                'meta_description' => 'Aligning COBIT, ISO 27001 and IT security governance for enterprise cyber defense.',
                'meta_keywords' => 'Cybersecurity, IT Governance, COBIT, ISO 27001, Information Security',
                'page_title' => 'Cybersecurity Governance & COBIT Alignment | ScholarAcad',
                'status' => 'Active',
                'company_id' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'course_category_id' => 50, // SAFe Scaled Agile Framework
                'course_id' => '15',
                'url_title' => 'safe-scaled-agile-framework-essential-guide',
                'resource_category' => 241, // Article
                'resource_title' => 'Scaling Agile with SAFe: Roles, ARTs, and PI Planning Demystified',
                'header_image' => 'assets/landingpage/blog2_image.png',
                'resource_auther' => 'SAFe Practice Consultant',
                'short_description' => 'Learn how Agile Release Trains (ARTs) synchronize multiple teams and how PI Planning drives organizational alignment.',
                'overview' => '<h2>What is Scaled Agile Framework (SAFe)?</h2><p>SAFe provides a knowledge base of proven, integrated principles, practices, and competencies for achieving business agility using Lean, Agile, and DevOps.</p>',
                'meta_description' => 'Comprehensive guide to Scaled Agile Framework (SAFe), PI Planning, and Agile Release Trains.',
                'meta_keywords' => 'SAFe, Scaled Agile, PI Planning, Agile Release Train, Business Agility',
                'page_title' => 'SAFe Scaled Agile Framework Essential Guide | ScholarAcad',
                'status' => 'Active',
                'company_id' => 1,
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($articles as $article) {
            DB::table('tbl_knowledge_center')->updateOrInsert(
                ['url_title' => $article['url_title']],
                $article
            );
        }

        echo "ResourceSeeder completed successfully: 4 master parent categories and " . count($articles) . " articles inserted/updated.\n";
    }
}
