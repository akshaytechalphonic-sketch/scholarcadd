import React, { useEffect, useState, useMemo } from 'react';
import Head from 'next/head';
import Link from 'next/link';
import Navbar from '@/Components/Navbar';
import Footer from '@/Components/Footer';
import Loader from '@/Components/loader';
import FormModal from '@/Components/FormModal';
import { DynamicSEO, generateSupportPageSchema } from "@/lib/seoHelper";
import { API_BASE_URL } from '../../apiconfig';
import {
  FaChevronDown,
  FaChevronUp,
  FaCheckCircle,
  FaBook,
  FaHome,
  FaDownload,
  FaFileAlt,
  FaLightbulb,
  FaRegClock,
  FaStar,
  FaBookmark,
} from 'react-icons/fa';
import { IoIosArrowForward } from 'react-icons/io';
import { MdOutlineNotes, MdVerified, MdMenuBook } from 'react-icons/md';

const safeHtml = (html) => {
  if (typeof window === 'undefined') return html || '';
  try {
    const DOMPurify = require('dompurify');
    return DOMPurify.sanitize(html || '');
  } catch (e) {
    return html || '';
  }
};

const normalizeImageUrl = (path, fallback = '/assets/landingpage/aboutus_bg.jpg') => {
  if (!path) return fallback;
  if (path.startsWith('http://') || path.startsWith('https://')) return path;
  const cleanPath = path.startsWith('/') ? path : `/${path}`;
  const base = (API_BASE_URL || '').replace(/\/+$/, '');
  return base ? `${base}${cleanPath}` : cleanPath;
};

export default function DedicatedNotesPage({
  courseData,
  loading = false,
  active_country,
  url_title,
}) {
  const [activeTab, setActiveTab] = useState(0);
  const [isMounted, setIsMounted] = useState(false);
  const [isModalOpen, setIsModalOpen] = useState(false);

  useEffect(() => {
    setIsMounted(true);
  }, []);

  const courseTitle = useMemo(
    () =>
      courseData?.support_page?.title ||
      courseData?.course?.name ||
      courseData?.courseHeading?.title ||
      courseData?.dynamicCourseHeading?.heading ||
      (url_title ? url_title.replace(/-/g, ' ').toUpperCase() : 'Study Notes'),
    [courseData, url_title]
  );

  const courseDescription = useMemo(
    () =>
      courseData?.seo?.description ||
      courseData?.courseDescription?.description ||
      courseData?.dynamicCourseHeading?.description ||
      '',
    [courseData]
  );

  const notesHtml = useMemo(() => {
    if (courseData?.support_page?.content) return courseData.support_page.content;
    if (Array.isArray(courseData?.aboutGeography) && courseData.aboutGeography.length > 0) {
      return courseData.aboutGeography.map((g) => g.description || '').join('<br/>');
    }
    return '';
  }, [courseData]);

  const keyfeatures = useMemo(() => {
    try {
      if (courseData?.keyfeatures) {
        return typeof courseData.keyfeatures === 'string'
          ? JSON.parse(courseData.keyfeatures)
          : courseData.keyfeatures;
      }
      return [];
    } catch (e) {
      return [];
    }
  }, [courseData]);

  const pdfUrl = useMemo(() => {
    if (courseData?.coursePDF?.curriculum_pdf_name) {
      return `${API_BASE_URL}/assets/courseCurriculum/${courseData.coursePDF.curriculum_pdf_name}`;
    }
    return null;
  }, [courseData]);

  const courseImage = useMemo(() => {
    const raw =
      courseData?.courseImage?.banner_image_path ||
      courseData?.certificationImage?.image_path ||
      courseData?.dynamicCourseHeading?.header_image;
    return normalizeImageUrl(raw);
  }, [courseData]);

  const courseSlug = url_title || '';
  const mainCourseUrl = `/${courseSlug}`;

  const canonicalPath = `/${courseSlug}/notes`;
  const seoTitle = `${courseTitle} - Complete Study Notes & Revision Guide | ScholarAcad`;
  const seoDesc = `Download curated study notes, quick formula sheets, revision summaries, and key concepts for ${courseTitle}.`;

  const schemaBreadcrumbs = [
    { name: "Home", url: "/" },
    { name: courseTitle, url: mainCourseUrl },
    { name: "Notes & Revision Guide", url: canonicalPath }
  ];

  const schemaData = useMemo(() => {
    return generateSupportPageSchema({
      title: seoTitle,
      description: seoDesc,
      pageUrl: canonicalPath,
      breadcrumbs: schemaBreadcrumbs
    });
  }, [seoTitle, seoDesc, canonicalPath, schemaBreadcrumbs]);

  if (loading && !courseData) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gray-50">
        <Loader />
      </div>
    );
  }

  return (
    <>
      <DynamicSEO
        title={seoTitle}
        description={seoDesc}
        keywords={`${courseTitle}, study notes, revision guide, formula sheet, pdf`}
        canonicalUrl={canonicalPath}
        ogType="article"
        ogImage={courseImage}
        schemaData={schemaData}
      />

      <Navbar />

      <main className="min-h-screen bg-[#f8fafc] text-gray-800">
        {/* Breadcrumb Header */}
        <div className="bg-white border-b border-gray-200">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <nav className="flex items-center space-x-2 text-xs sm:text-sm text-gray-500 overflow-x-auto whitespace-nowrap">
              <Link
                href="/"
                className="flex items-center hover:text-blue-600 transition"
              >
                <FaHome className="mr-1 text-sm" /> Home
              </Link>
              <IoIosArrowForward className="text-gray-400 text-xs shrink-0" />
              <Link
                href={mainCourseUrl}
                className="hover:text-blue-600 transition capitalize truncate max-w-[200px]"
              >
                {courseTitle}
              </Link>
              <IoIosArrowForward className="text-gray-400 text-xs shrink-0" />
              <span className="text-blue-600 font-semibold">Notes & Revision Guide</span>
            </nav>
          </div>
        </div>

        {/* Hero Section */}
        <section className="bg-gradient-to-r from-[#491689] via-[#6520b8] to-[#7f26d9] text-white py-12 lg:py-16 relative overflow-hidden shadow-md">
          <div className="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
              {/* Left Column */}
              <div className="lg:col-span-7">
                <div className="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 text-purple-100 border border-white/25 text-xs font-semibold mb-4 backdrop-blur-sm">
                  <MdOutlineNotes className="text-sm" />
                  Curated High-Yield Revision Material
                </div>
                <h1 className="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight">
                  {courseTitle}{' '}
                  <span className="text-transparent bg-clip-text bg-gradient-to-r from-purple-200 to-pink-200">
                    Study Notes
                  </span>
                </h1>
                <p className="mt-4 text-base sm:text-lg text-purple-100/90 leading-relaxed line-clamp-3">
                  {courseDescription ||
                    'Access high-yield revision summaries, key framework cheatsheets, exam tips, and downloadable PDF study notes crafted by domain experts.'}
                </p>

                {/* Highlights */}
                <div className="mt-6 flex flex-wrap items-center gap-4 text-xs sm:text-sm text-gray-300">
                  <div className="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-sm">
                    <FaBookmark className="text-cyan-400" />
                    <span>Exam-Focused Summaries</span>
                  </div>
                  <div className="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-sm">
                    <MdVerified className="text-teal-300" />
                    <span>Instructor Verified</span>
                  </div>
                  <div className="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-sm">
                    <FaStar className="text-amber-400" />
                    <span>Quick Recall Cheat Sheets</span>
                  </div>
                </div>

                {/* Actions */}
                <div className="mt-8 flex flex-wrap gap-4">
                  {pdfUrl && (
                    <a
                      href={pdfUrl}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 text-white font-semibold text-sm shadow-lg hover:shadow-teal-500/30 transition transform hover:-translate-y-0.5"
                    >
                      <FaDownload /> Download Complete Study PDF
                    </a>
                  )}
                  <button
                    onClick={() => setIsModalOpen(true)}
                    className="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white font-semibold text-sm shadow-lg hover:shadow-cyan-500/30 transition transform hover:-translate-y-0.5"
                  >
                    <FaFileAlt /> Request Sample Notes
                  </button>
                  <Link
                    href={mainCourseUrl}
                    className="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white/15 hover:bg-white/25 text-white font-semibold text-sm backdrop-blur-sm transition"
                  >
                    Course Details
                  </Link>
                </div>
              </div>

              {/* Right Column: Course Image Card */}
              <div className="lg:col-span-5 flex justify-center lg:justify-end">
                <div className="relative w-full max-w-[420px] rounded-2xl overflow-hidden shadow-2xl border-2 border-white/20 bg-slate-900/60 backdrop-blur-md p-3 group">
                  <div className="overflow-hidden rounded-xl bg-slate-800 flex items-center justify-center min-h-[220px]">
                    <img
                      src={courseImage}
                      alt={courseTitle}
                      onError={(e) => {
                        e.currentTarget.src = '/assets/landingpage/aboutus_bg.jpg';
                      }}
                      className="w-full h-56 sm:h-64 object-cover group-hover:scale-105 transition-transform duration-500"
                    />
                  </div>
                  <div className="p-3 text-center">
                    <span className="text-xs font-semibold text-teal-300 tracking-wide uppercase">
                      Comprehensive Revision Notes
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* Content Section */}
        <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {/* Left 2 Columns */}
            <div className="lg:col-span-2 space-y-8">
              {/* Dynamic Notes Content */}
              {notesHtml && (
                <div className="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100">
                  <h2 className="text-xl sm:text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <MdMenuBook className="text-cyan-600" />
                    Study Notes & Guide
                  </h2>
                  <div
                    className="prose max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-700 prose-li:text-gray-700 prose-cyan"
                    dangerouslySetInnerHTML={{
                      __html: isMounted ? safeHtml(notesHtml) : notesHtml,
                    }}
                  />
                </div>
              )}

              {/* Key Features & High-Yield Summary */}
              {keyfeatures && keyfeatures.length > 0 && (
                <div className="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100">
                  <h2 className="text-xl sm:text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <FaLightbulb className="text-amber-500" />
                    Key Focus Areas & Core Takeaways
                  </h2>
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {keyfeatures.map((kf, idx) => (
                      <div
                        key={idx}
                        className="p-4 rounded-xl bg-cyan-50/50 border border-cyan-100 hover:border-cyan-300 transition"
                      >
                        <h4 className="font-semibold text-gray-900 text-sm mb-1 flex items-center gap-2">
                          <FaCheckCircle className="text-cyan-600 text-sm shrink-0" />
                          {typeof kf === 'string' ? kf : kf?.title || `Topic ${idx + 1}`}
                        </h4>
                        {kf?.description && (
                          <p className="text-xs text-gray-600 ml-5">
                            {kf.description}
                          </p>
                        )}
                      </div>
                    ))}
                  </div>
                </div>
              )}

              {/* Study Notes & Revision Chapters */}
              <div className="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100">
                <h2 className="text-xl sm:text-2xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                  <MdMenuBook className="text-cyan-600" />
                  Structured Revision Chapters
                </h2>
                <p className="text-sm text-gray-600 mb-6">
                  Compact revision blocks summarizing core definitions, models, exam formulas, and high-frequency questions.
                </p>

                <div className="space-y-4">
                  <div className="border border-gray-200 rounded-xl p-5 hover:border-cyan-400 transition bg-slate-50/60">
                    <div className="flex items-center justify-between">
                      <span className="text-xs font-bold uppercase tracking-wider text-cyan-700 bg-cyan-100 px-2.5 py-0.5 rounded-full">
                        Chapter 1
                      </span>
                      <span className="text-xs text-gray-500">Core Concepts</span>
                    </div>
                    <h3 className="font-bold text-gray-900 text-base mt-2">
                      Key Terminology, Frameworks & Definitions
                    </h3>
                    <p className="text-sm text-gray-600 mt-1">
                      Quick overview of fundamental terminology, official acronyms, and standard exam definitions.
                    </p>
                  </div>

                  <div className="border border-gray-200 rounded-xl p-5 hover:border-cyan-400 transition bg-slate-50/60">
                    <div className="flex items-center justify-between">
                      <span className="text-xs font-bold uppercase tracking-wider text-cyan-700 bg-cyan-100 px-2.5 py-0.5 rounded-full">
                        Chapter 2
                      </span>
                      <span className="text-xs text-gray-500">Process & Execution</span>
                    </div>
                    <h3 className="font-bold text-gray-900 text-base mt-2">
                      Execution Flowcharts & Process Life Cycles
                    </h3>
                    <p className="text-sm text-gray-600 mt-1">
                      Visual cheatsheets, input/output process mapping, and RACI governance matrices.
                    </p>
                  </div>

                  <div className="border border-gray-200 rounded-xl p-5 hover:border-cyan-400 transition bg-slate-50/60">
                    <div className="flex items-center justify-between">
                      <span className="text-xs font-bold uppercase tracking-wider text-cyan-700 bg-cyan-100 px-2.5 py-0.5 rounded-full">
                        Chapter 3
                      </span>
                      <span className="text-xs text-gray-500">Exam Strategy</span>
                    </div>
                    <h3 className="font-bold text-gray-900 text-base mt-2">
                      Exam Tips, Common Pitfalls & High-Scoring Tactics
                    </h3>
                    <p className="text-sm text-gray-600 mt-1">
                      Proven time management guidelines, elimination techniques for MCQs, and sample practice scenarios.
                    </p>
                  </div>
                </div>
              </div>

              {/* Download CTA Banner */}
              <div className="rounded-2xl p-6 sm:p-8 bg-gradient-to-r from-cyan-900 to-slate-900 text-white shadow-md">
                <div className="flex flex-col sm:flex-row items-center justify-between gap-6">
                  <div>
                    <h3 className="text-lg sm:text-xl font-bold">
                      Need the Full Study Pack & Mock Question Papers?
                    </h3>
                    <p className="text-sm text-gray-300 mt-1 max-w-md">
                      Get full access to mock tests, instructor lecture slides, mind-maps, and revision notes.
                    </p>
                  </div>
                  <button
                    onClick={() => setIsModalOpen(true)}
                    className="shrink-0 px-6 py-3 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-900 font-bold text-sm shadow-lg transition"
                  >
                    Download Study Pack
                  </button>
                </div>
              </div>
            </div>

            {/* Right 1 Column */}
            <div className="space-y-6">
              <div className="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 sticky top-24">
                <h3 className="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-100">
                  Notes Features
                </h3>
                <ul className="space-y-3.5 text-sm">
                  <li className="flex items-center justify-between text-gray-600">
                    <span className="flex items-center gap-2">
                      <FaFileAlt className="text-cyan-600" /> Format
                    </span>
                    <span className="font-semibold text-gray-800">
                      PDF & Printable
                    </span>
                  </li>
                  <li className="flex items-center justify-between text-gray-600">
                    <span className="flex items-center gap-2">
                      <FaRegClock className="text-cyan-600" /> Revision Time
                    </span>
                    <span className="font-semibold text-gray-800">
                      ~3 Hours Rapid
                    </span>
                  </li>
                  <li className="flex items-center justify-between text-gray-600">
                    <span className="flex items-center gap-2">
                      <MdVerified className="text-cyan-600" /> Syllabus Match
                    </span>
                    <span className="font-semibold text-teal-600">
                      100% Aligned
                    </span>
                  </li>
                </ul>

                <div className="mt-6 pt-4 border-t border-gray-100 space-y-3">
                  <button
                    onClick={() => setIsModalOpen(true)}
                    className="w-full py-3 px-4 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white font-semibold text-sm text-center shadow-md hover:shadow-cyan-500/20 transition"
                  >
                    Get Free Sample Notes
                  </button>
                  <Link
                    href={mainCourseUrl}
                    className="block w-full py-2.5 px-4 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm text-center transition"
                  >
                    Back to Course
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>

      <Footer />

      {isModalOpen && (
        <FormModal
          isOpen={isModalOpen}
          onClose={() => setIsModalOpen(false)}
          courseTitle={courseTitle}
        />
      )}
    </>
  );
}
