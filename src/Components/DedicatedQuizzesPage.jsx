import React, { useState, useEffect, useMemo } from "react";
import Head from "next/head";
import Link from "next/link";
import Navbar from "@/Components/Navbar";
import Footer from "@/Components/Footer";
import Loader from "@/Components/loader";
import { API_BASE_URL } from "../../apiconfig";
import { useAuth } from "@/context/AuthContext";
import { DynamicSEO, getCleanCanonicalUrl, generateSupportPageSchema } from "@/lib/seoHelper";
import {
  FaClock,
  FaQuestionCircle,
  FaRocket,
  FaCheckCircle,
  FaAward,
  FaPlay,
  FaExclamationTriangle,
  FaArrowRight,
  FaSearch,
  FaBookOpen,
  FaHistory,
  FaSave,
  FaTrashAlt,
} from "react-icons/fa";
import { IoIosArrowForward } from "react-icons/io";
import { IoHomeOutline } from "react-icons/io5";
import { MdQuiz, MdTimer, MdOutlineLayers, MdChecklist } from "react-icons/md";
import { BsCheckCircleFill, BsLightningChargeFill } from "react-icons/bs";

export default function DedicatedQuizzesPage({
  courseSlug: initialCourseSlug,
  initialQuizzes = null,
  courseData = null,
  active_country = "in",
  url_title,
}) {
  const { token } = useAuth();
  const [quizzes, setQuizzes] = useState(initialQuizzes || []);
  const [courseInfo, setCourseInfo] = useState(courseData || null);
  const [loading, setLoading] = useState(!initialQuizzes);
  const [error, setError] = useState(null);
  const [selectedFilter, setSelectedFilter] = useState("all");
  const [searchQuery, setSearchQuery] = useState("");
  const [hasSavedAttempt, setHasSavedAttempt] = useState(false);

  const targetCourseSlug = useMemo(() => {
    return (
      initialCourseSlug ||
      url_title ||
      courseData?.course?.slug ||
      courseData?.slug ||
      ""
    );
  }, [initialCourseSlug, url_title, courseData]);

  // Check saved attempt in localStorage
  useEffect(() => {
    try {
      const keys = Object.keys(localStorage);
      const hasQuizAttempt = keys.some((k) => k.startsWith("scholaracad_quiz_attempt_"));
      setHasSavedAttempt(hasQuizAttempt);
    } catch (e) {
      // ignore
    }
  }, []);

  const handleClearSaved = () => {
    try {
      const keys = Object.keys(localStorage);
      keys.forEach((k) => {
        if (k.startsWith("scholaracad_quiz_attempt_")) {
          localStorage.removeItem(k);
        }
      });
      setHasSavedAttempt(false);
      alert("Saved attempt cleared successfully.");
    } catch (e) {
      // ignore
    }
  };

  // Client-side fetch if quizzes were not loaded via SSR
  useEffect(() => {
    if (!targetCourseSlug) return;
    if (initialQuizzes && initialQuizzes.length > 0) {
      setQuizzes(initialQuizzes);
      setLoading(false);
      return;
    }

    let isMounted = true;
    async function fetchQuizzes() {
      setLoading(true);
      setError(null);
      try {
        const headers = {
          Accept: "application/json",
        };
        if (token) {
          headers.Authorization = `Bearer ${token}`;
        }

        const endpoint = `${API_BASE_URL}/api/courses/${targetCourseSlug}/quizzes`;
        const res = await fetch(endpoint, {
          method: "GET",
          headers,
        });

        if (!res.ok) {
          if (res.status === 404) {
            if (isMounted) {
              setQuizzes([]);
              setError("No quizzes found for this course.");
            }
          } else {
            if (isMounted) {
              setError("Failed to fetch quizzes. Please try again later.");
            }
          }
          if (isMounted) setLoading(false);
          return;
        }

        const data = await res.json();
        if (isMounted) {
          if (data?.status && Array.isArray(data?.data)) {
            setQuizzes(data.data);
            if (data.course) {
              setCourseInfo(data.course);
            }
          } else if (Array.isArray(data)) {
            setQuizzes(data);
          } else {
            setQuizzes([]);
          }
          setError(null);
        }
      } catch (err) {
        console.error("Error fetching quizzes:", err);
        if (isMounted) {
          setError("Network error while loading quizzes. Please check your connection.");
        }
      } finally {
        if (isMounted) setLoading(false);
      }
    }

    fetchQuizzes();

    return () => {
      isMounted = false;
    };
  }, [targetCourseSlug, token, initialQuizzes]);

  // Display course title
  const courseDisplayName = useMemo(() => {
    if (courseInfo?.name) return courseInfo.name;
    if (courseInfo?.title) return courseInfo.title;
    if (courseData?.course?.name) return courseData.course.name;
    if (targetCourseSlug) {
      return targetCourseSlug
        .split("-")
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(" ");
    }
    return "Exam Simulator";
  }, [courseInfo, courseData, targetCourseSlug]);

  // Total questions count calculation
  const totalQuestionsSum = useMemo(() => {
    if (!Array.isArray(quizzes) || quizzes.length === 0) return 180;
    return quizzes.reduce((sum, q) => sum + (Number(q?.total_questions) || 0), 0);
  }, [quizzes]);

  // Primary mock quiz and practice quiz from list
  const primaryMockQuiz = useMemo(() => {
    return quizzes.find((q) => (q?.type || "").toLowerCase().includes("mock")) || quizzes[0];
  }, [quizzes]);

  const primaryPracticeQuiz = useMemo(() => {
    return quizzes.find((q) => (q?.type || "").toLowerCase().includes("practice")) || quizzes[1] || quizzes[0];
  }, [quizzes]);

  // Filter quizzes by type and search query
  const filteredQuizzes = useMemo(() => {
    if (!Array.isArray(quizzes)) return [];
    return quizzes.filter((quiz) => {
      const matchesFilter =
        selectedFilter === "all" ||
        quiz?.type?.toLowerCase() === selectedFilter.toLowerCase() ||
        (selectedFilter === "mock" && quiz?.type?.toLowerCase().includes("mock")) ||
        (selectedFilter === "practice" && quiz?.type?.toLowerCase().includes("practice"));

      const matchesSearch =
        !searchQuery ||
        quiz?.title?.toLowerCase().includes(searchQuery.toLowerCase()) ||
        quiz?.description?.toLowerCase().includes(searchQuery.toLowerCase()) ||
        quiz?.type?.toLowerCase().includes(searchQuery.toLowerCase());

      return matchesFilter && matchesSearch;
    });
  }, [quizzes, selectedFilter, searchQuery]);

  // Helper for human-readable duration
  const formatDuration = (minutes) => {
    if (!minutes && minutes !== 0) return "180 Minutes";
    const mins = Number(minutes);
    if (mins >= 60) {
      const hrs = Math.floor(mins / 60);
      const rem = mins % 60;
      return rem > 0 ? `${hrs}h ${rem}m (${mins} mins)` : `${hrs} hrs (${mins} mins)`;
    }
    return `${mins} Minutes`;
  };

  const quizzesPageSchema = useMemo(() => {
    return generateSupportPageSchema({
      pageTitle: `${courseDisplayName} Quizzes & Exam Simulator`,
      pageDescription: `Standalone offline/online practice and exam simulator for ${courseDisplayName}. Practice mode, timed exam mode, instant scoring, and verified question bank.`,
      pageUrl: `/${targetCourseSlug}/quizzes`,
      courseName: courseDisplayName,
      breadcrumbs: [
        { name: "Home", url: "/" },
        { name: courseDisplayName, url: `/${targetCourseSlug}` },
        { name: "Quizzes", url: `/${targetCourseSlug}/quizzes` },
      ],
    });
  }, [courseDisplayName, targetCourseSlug]);

  return (
    <>
      <DynamicSEO
        title={`${courseDisplayName} — Quizzes & Exam Simulator | ScholarAcad`}
        description={`Standalone practice and exam simulator for ${courseDisplayName}. Practice mode, timed exam mode, instant scoring, and verified question bank.`}
        canonicalUrl={getCleanCanonicalUrl(`/${targetCourseSlug}/quizzes`)}
        keywords={`${courseDisplayName} mock exam, ${courseDisplayName} practice test, quizzes`}
        ogTitle={`${courseDisplayName} — Quizzes & Exam Simulator | ScholarAcad`}
        ogDescription={`Standalone practice and exam simulator for ${courseDisplayName}.`}
        ogUrl={getCleanCanonicalUrl(`/${targetCourseSlug}/quizzes`)}
        schemaData={quizzesPageSchema}
        robots="index, follow"
      />

      <Navbar />

      <main className="min-h-screen bg-[#f3f4f8]">
        {/* Top Header Banner matching the Exact Blue-Purple Vibrant Gradient UI */}
        <header className="relative bg-gradient-to-r from-[#491689] via-[#6520b8] to-[#7f26d9] text-white pt-10 pb-12 px-4 sm:px-6 lg:px-8 text-center shadow-md">
          <div className="max-w-6xl mx-auto space-y-4">
            {/* Breadcrumb */}
            <nav className="flex items-center justify-center flex-wrap gap-2 text-xs text-purple-200 mb-2">
              <Link
                href="/"
                className="hover:text-white flex items-center gap-1.5 transition-colors"
              >
                <IoHomeOutline className="text-sm" />
                <span>Home</span>
              </Link>
              <IoIosArrowForward className="text-purple-300 text-xs" />
              <Link
                href="/all-courses"
                className="hover:text-white transition-colors"
              >
                Courses
              </Link>
              <IoIosArrowForward className="text-purple-300 text-xs" />
              <Link
                href={`/${targetCourseSlug}`}
                className="hover:text-white line-clamp-1 max-w-[200px] sm:max-w-none transition-colors"
              >
                {courseDisplayName}
              </Link>
              <IoIosArrowForward className="text-purple-300 text-xs" />
              <span className="text-amber-300 font-semibold">Exam Simulator</span>
            </nav>

            {/* Title with Rocket Icon */}
            <div className="flex items-center justify-center gap-3">
              <span className="text-2xl sm:text-3xl">🚀</span>
              <h1 className="text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-tight text-white">
                {courseDisplayName} — Enhanced Exam Simulator
              </h1>
            </div>

            {/* Subtitle description */}
            <p className="text-xs sm:text-sm text-purple-100/90 max-w-3xl mx-auto leading-relaxed">
              Standalone offline/online practice/exam simulator generated from the official question bank. Practice mode shows explanations immediately, while Exam mode hides answers until final submission.
            </p>

            {/* Feature Capsule Badges (Matches screenshot pill buttons) */}
            <div className="flex flex-wrap items-center justify-center gap-2 sm:gap-2.5 pt-3">
              <span className="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white/10 hover:bg-white/15 border border-white/25 text-white backdrop-blur-sm transition">
                Practice Mode
              </span>
              <span className="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white/10 hover:bg-white/15 border border-white/25 text-white backdrop-blur-sm transition">
                Exam Mode with Timer
              </span>
              <span className="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white/10 hover:bg-white/15 border border-white/25 text-white backdrop-blur-sm transition">
                Review Wrong / Flagged / Unanswered
              </span>
              <span className="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white/10 hover:bg-white/15 border border-white/25 text-white backdrop-blur-sm transition">
                Highlight + Strike Through
              </span>
              <span className="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white/10 hover:bg-white/15 border border-white/25 text-white backdrop-blur-sm transition">
                Source Images Embedded
              </span>
            </div>
          </div>
        </header>

        {/* Main Content Area */}
        <section className="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-10 space-y-8">
          {/* Card 1: Choose How You Want To Study (Matches 3 Study Modes) */}
          <div className="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200/90 p-6 sm:p-8 space-y-6">
            <div>
              <h2 className="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Choose how you want to study
              </h2>
              <p className="text-xs sm:text-sm text-slate-500 mt-1">
                Select your preferred learning mode or continue where you left off.
              </p>
            </div>

            {/* 3 Main Study Mode Cards (Screenshot design) */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-5">
              {/* Box 1: Practice Mode */}
              <div className="bg-slate-50/70 hover:bg-slate-50 border border-slate-200/80 rounded-2xl p-5 flex flex-col justify-between space-y-4 transition">
                <div className="space-y-2.5">
                  <div className="flex items-center gap-2 text-pink-600 font-bold text-sm">
                    <span className="w-2.5 h-2.5 rounded-full bg-pink-500"></span>
                    <span>Practice Mode</span>
                  </div>
                  <p className="text-xs text-slate-500 leading-relaxed">
                    One question at a time. After choosing an answer, the correct option and explanation appear immediately.
                  </p>
                </div>

                <Link
                  href={
                    primaryPracticeQuiz
                      ? `/${active_country || "in"}/${targetCourseSlug}/quizzes/${primaryPracticeQuiz.slug || primaryPracticeQuiz.id}`
                      : `/${active_country || "in"}/${targetCourseSlug}/quizzes`
                  }
                  className="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-[#491689] hover:bg-[#390f6e] text-white text-center shadow-sm transition block cursor-pointer"
                >
                  Start Practice
                </Link>
              </div>

              {/* Box 2: Exam Mode with Timer */}
              <div className="bg-slate-50/70 hover:bg-slate-50 border border-slate-200/80 rounded-2xl p-5 flex flex-col justify-between space-y-4 transition">
                <div className="space-y-2.5">
                  <div className="flex items-center gap-2 text-indigo-700 font-bold text-sm">
                    <MdTimer className="text-base text-indigo-600" />
                    <span>Exam Mode</span>
                  </div>
                  <p className="text-xs text-slate-500 leading-relaxed">
                    One question at a time with a full exam timer. Explanations and correct answers are hidden until you submit.
                  </p>
                  <div className="flex items-center gap-2 text-xs text-slate-600 pt-1">
                    <span className="font-semibold text-slate-500">Timer minutes:</span>
                    <span className="px-2.5 py-0.5 bg-white border border-slate-200 rounded-md font-bold text-slate-800">
                      {primaryMockQuiz?.duration_minutes || 180}
                    </span>
                  </div>
                </div>

                <Link
                  href={
                    primaryMockQuiz
                      ? `/${active_country || "in"}/${targetCourseSlug}/quizzes/${primaryMockQuiz.slug || primaryMockQuiz.id}`
                      : `/${active_country || "in"}/${targetCourseSlug}/quizzes`
                  }
                  className="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-[#6520b8] hover:bg-[#53169c] text-white text-center shadow-sm transition block cursor-pointer"
                >
                  Start Exam
                </Link>
              </div>

              {/* Box 3: Resume Saved Attempt */}
              <div className="bg-slate-50/70 hover:bg-slate-50 border border-slate-200/80 rounded-2xl p-5 flex flex-col justify-between space-y-4 transition">
                <div className="space-y-2.5">
                  <div className="flex items-center gap-2 text-purple-700 font-bold text-sm">
                    <FaSave className="text-sm text-purple-600" />
                    <span>Resume</span>
                  </div>
                  <p className="text-xs text-slate-500 leading-relaxed">
                    Continue your saved attempt from this browser. Saved progress stays inside this HTML/browser only.
                  </p>
                </div>

                <div className="flex items-center gap-2 pt-1">
                  <Link
                    href={
                      primaryMockQuiz
                        ? `/${active_country || "in"}/${targetCourseSlug}/quizzes/${primaryMockQuiz.slug || primaryMockQuiz.id}/questions`
                        : `/${active_country || "in"}/${targetCourseSlug}/quizzes`
                    }
                    className="flex-1 py-2 px-2.5 rounded-xl text-[11px] font-bold bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 text-center shadow-sm transition block truncate cursor-pointer"
                  >
                    Resume Saved Attempt
                  </Link>

                  <button
                    type="button"
                    onClick={handleClearSaved}
                    className="py-2 px-2.5 rounded-xl text-[11px] font-bold bg-white hover:bg-red-50 text-slate-500 hover:text-red-600 border border-slate-200 transition cursor-pointer"
                    title="Clear saved progress"
                  >
                    Clear Saved
                  </button>
                </div>
              </div>
            </div>

            {/* Sub-Section: Available Question Sets in Bank */}
            <div className="border-t border-slate-100 pt-6">
              <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <h3 className="text-base font-bold text-slate-900 flex items-center gap-2">
                  <MdOutlineLayers className="text-[#6520b8]" />
                  <span>Available Question Bank Sets ({quizzes.length})</span>
                </h3>

                {/* Filter Pills */}
                <div className="flex items-center gap-2">
                  <button
                    onClick={() => setSelectedFilter("all")}
                    className={`px-3 py-1 rounded-lg text-xs font-bold transition cursor-pointer ${
                      selectedFilter === "all"
                        ? "bg-[#6520b8] text-white"
                        : "bg-slate-100 text-slate-600 hover:bg-slate-200"
                    }`}
                  >
                    All
                  </button>
                  <button
                    onClick={() => setSelectedFilter("mock")}
                    className={`px-3 py-1 rounded-lg text-xs font-bold transition cursor-pointer ${
                      selectedFilter === "mock"
                        ? "bg-[#6520b8] text-white"
                        : "bg-slate-100 text-slate-600 hover:bg-slate-200"
                    }`}
                  >
                    Mock
                  </button>
                  <button
                    onClick={() => setSelectedFilter("practice")}
                    className={`px-3 py-1 rounded-lg text-xs font-bold transition cursor-pointer ${
                      selectedFilter === "practice"
                        ? "bg-[#6520b8] text-white"
                        : "bg-slate-100 text-slate-600 hover:bg-slate-200"
                    }`}
                  >
                    Practice
                  </button>
                </div>
              </div>

              {/* Loading State */}
              {loading && (
                <div className="py-10 flex flex-col items-center justify-center space-y-3">
                  <Loader />
                  <p className="text-slate-500 text-xs font-medium animate-pulse">
                    Loading quizzes from question bank...
                  </p>
                </div>
              )}

              {/* Error State */}
              {!loading && error && (
                <div className="bg-red-50 border border-red-200 rounded-2xl p-5 text-center max-w-md mx-auto my-4">
                  <FaExclamationTriangle className="text-red-500 text-xl mx-auto mb-1.5" />
                  <h4 className="text-xs font-bold text-red-900 mb-1">
                    Unable to load quizzes
                  </h4>
                  <p className="text-xs text-red-700 mb-3">{error}</p>
                </div>
              )}

              {/* Question Sets Grid */}
              {!loading && !error && filteredQuizzes.length > 0 && (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                  {filteredQuizzes.map((quiz, index) => {
                    const isMock = (quiz?.type || "").toLowerCase().includes("mock");

                    return (
                      <div
                        key={quiz?.id || quiz?.slug || index}
                        className="bg-white border border-slate-200/90 hover:border-purple-300 rounded-2xl p-4 sm:p-5 flex flex-col justify-between hover:shadow-md transition-all duration-300 group"
                      >
                        <div>
                          <div className="flex items-center justify-between gap-2 mb-2.5">
                            <span
                              className={`inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold ${
                                isMock
                                  ? "bg-purple-50 text-purple-700 border border-purple-200"
                                  : "bg-emerald-50 text-emerald-700 border border-emerald-200"
                              }`}
                            >
                              <span
                                className={`w-1.5 h-1.5 rounded-full ${
                                  isMock ? "bg-purple-600 animate-pulse" : "bg-emerald-600"
                                }`}
                              ></span>
                              {isMock ? "Mock Exam" : "Practice Quiz"}
                            </span>

                            <span className="text-[11px] text-slate-400 font-semibold">
                              #{index + 1}
                            </span>
                          </div>

                          <h4 className="text-sm font-bold text-slate-900 group-hover:text-[#6520b8] transition-colors line-clamp-1 mb-1.5">
                            {quiz?.title || "Exam Set"}
                          </h4>

                          <p className="text-xs text-slate-500 line-clamp-2 mb-4 leading-relaxed">
                            {quiz?.description ||
                              "Comprehensive questions designed to match real exam conditions with detailed answer explanations."}
                          </p>
                        </div>

                        <div className="space-y-3">
                          <div className="grid grid-cols-2 gap-2 py-2 px-2.5 bg-slate-50 rounded-xl border border-slate-100 text-[11px]">
                            <div>
                              <span className="text-[9px] text-slate-400 uppercase font-bold tracking-wider block">
                                Questions
                              </span>
                              <span className="font-bold text-slate-800">
                                {quiz?.total_questions || 0} Items
                              </span>
                            </div>
                            <div className="border-l border-slate-200 pl-2">
                              <span className="text-[9px] text-slate-400 uppercase font-bold tracking-wider block">
                                Duration
                              </span>
                              <span className="font-bold text-slate-800">
                                {formatDuration(quiz?.duration_minutes)}
                              </span>
                            </div>
                          </div>

                          <Link
                            href={`/${targetCourseSlug}/quizzes/${quiz?.slug || quiz?.id}`}
                            className={`w-full py-2 px-3 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition-all cursor-pointer ${
                              isMock
                                ? "bg-[#6520b8] hover:bg-[#53169c] text-white shadow-sm"
                                : "bg-[#491689] hover:bg-[#390f6e] text-white shadow-sm"
                            }`}
                          >
                            <FaPlay className="text-[9px]" />
                            <span>{isMock ? "Start Exam" : "Start Practice"}</span>
                            <FaArrowRight className="text-[10px] ml-0.5 group-hover:translate-x-0.5 transition-transform" />
                          </Link>
                        </div>
                      </div>
                    );
                  })}
                </div>
              )}
            </div>
          </div>

          {/* Card 2: Quiz Overview (Matches bottom section of screenshot) */}
          <div className="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200/90 p-6 sm:p-8">
            <h2 className="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight mb-6">
              Quiz Overview
            </h2>

            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
              {/* Stat 1: Total Items */}
              <div className="bg-slate-50/70 border border-slate-200/80 rounded-2xl p-5">
                <div className="text-2xl sm:text-3xl font-extrabold text-[#491689] mb-1">
                  {loading ? "..." : totalQuestionsSum}
                </div>
                <div className="text-xs font-bold text-slate-800 mb-1">
                  Total Items
                </div>
                <p className="text-[11px] text-slate-500 leading-normal">
                  Includes duplicate source numbering exactly as present in source.
                </p>
              </div>

              {/* Stat 2: 1x1 Display */}
              <div className="bg-slate-50/70 border border-slate-200/80 rounded-2xl p-5">
                <div className="text-2xl sm:text-3xl font-extrabold text-[#491689] mb-1">
                  1×1
                </div>
                <div className="text-xs font-bold text-slate-800 mb-1">
                  Question display
                </div>
                <p className="text-[11px] text-slate-500 leading-normal">
                  One question at a time for focused practice and test conditions.
                </p>
              </div>

              {/* Stat 3: CSV Export */}
              <div className="bg-slate-50/70 border border-slate-200/80 rounded-2xl p-5">
                <div className="text-2xl sm:text-3xl font-extrabold text-[#491689] mb-1">
                  CSV
                </div>
                <div className="text-xs font-bold text-slate-800 mb-1">
                  Result export
                </div>
                <p className="text-[11px] text-slate-500 leading-normal">
                  Download detailed results and review question breakdowns.
                </p>
              </div>

              {/* Stat 4: Offline / Standalone */}
              <div className="bg-slate-50/70 border border-slate-200/80 rounded-2xl p-5">
                <div className="text-2xl sm:text-3xl font-extrabold text-[#491689] mb-1">
                  Offline
                </div>
                <div className="text-xs font-bold text-slate-800 mb-1">
                  Standalone
                </div>
                <p className="text-[11px] text-slate-500 leading-normal">
                  Open directly in a browser with instant response and fast loading.
                </p>
              </div>
            </div>
          </div>

          {/* Footer Subtext (Matches bottom text in screenshot) */}
          <div className="text-center text-[11px] text-slate-400 py-2">
            Enhanced {courseDisplayName} Simulator. Questions, answer keys, explanations, highlights, strike-through choices, flags, and progress are embedded in this standalone browser experience.
          </div>
        </section>
      </main>

      <Footer />
    </>
  );
}
