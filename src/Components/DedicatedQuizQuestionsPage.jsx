import React, { useState, useEffect, useMemo, useRef } from "react";
import Head from "next/head";
import Link from "next/link";
import { useRouter } from "next/router";
import Navbar from "@/Components/Navbar";
import Footer from "@/Components/Footer";
import Loader from "@/Components/loader";
import { API_BASE_URL } from "../../apiconfig";
import { useAuth } from "@/context/AuthContext";
import { DynamicSEO, getCleanCanonicalUrl } from "@/lib/seoHelper";
import {
  FaArrowLeft,
  FaArrowRight,
  FaCheckCircle,
  FaExclamationTriangle,
  FaRegCircle,
  FaFlag,
  FaRegFlag,
  FaClock,
  FaRedo,
  FaTrophy,
  FaCheck,
  FaTimes,
  FaChartPie,
  FaListOl,
} from "react-icons/fa";
import { IoCloseOutline } from "react-icons/io5";
import {
  MdQuiz,
  MdFormatListNumbered,
  MdOutlineSend,
  MdOutlineAssignmentTurnedIn,
  MdOutlineTimer,
} from "react-icons/md";

export default function DedicatedQuizQuestionsPage({
  courseSlug: initialCourseSlug,
  quizSlug: initialQuizSlug,
  initialData = null,
  active_country = "in",
  url_title,
}) {
  const router = useRouter();
  const { token } = useAuth();
  const [data, setData] = useState(initialData || null);
  const [loading, setLoading] = useState(!initialData);
  const [error, setError] = useState(null);

  // Attempt metadata
  const attemptId = router?.query?.attempt_id || null;
  const attemptMode = router?.query?.mode || "exam";

  // Simulator flow state
  const [currentIndex, setCurrentIndex] = useState(0);
  const [selectedAnswers, setSelectedAnswers] = useState({});
  const [flaggedQuestions, setFlaggedQuestions] = useState({});
  const [isSubmitted, setIsSubmitted] = useState(false);
  const [showSubmitModal, setShowSubmitModal] = useState(false);
  const [timeRemaining, setTimeRemaining] = useState(null);
  const [timeSpentSeconds, setTimeSpentSeconds] = useState(0);

  const courseSlug = useMemo(() => {
    return (
      initialCourseSlug ||
      url_title ||
      data?.course?.slug ||
      ""
    );
  }, [initialCourseSlug, url_title, data]);

  const quizSlug = useMemo(() => {
    return initialQuizSlug || data?.quiz?.slug || "";
  }, [initialQuizSlug, data]);

  // Client-side fetch questions API
  useEffect(() => {
    if (!courseSlug || !quizSlug) return;
    if (initialData && initialData.questions) {
      setData(initialData);
      setLoading(false);
      return;
    }

    let isMounted = true;
    async function fetchQuestions() {
      setLoading(true);
      setError(null);
      try {
        const headers = {
          Accept: "application/json",
        };
        if (token) {
          headers.Authorization = `Bearer ${token}`;
        }

        const endpoint = `${API_BASE_URL}/api/courses/${courseSlug}/quizzes/${quizSlug}/questions`;
        const res = await fetch(endpoint, {
          method: "GET",
          headers,
        });

        if (!res.ok) {
          if (res.status === 404) {
            if (isMounted) setError("Quiz questions not found.");
          } else {
            if (isMounted) setError("Failed to fetch questions. Please try again later.");
          }
          if (isMounted) setLoading(false);
          return;
        }

        const json = await res.json();
        if (isMounted) {
          if (json?.status && json?.data) {
            setData(json.data);
            setError(null);
          } else {
            setError("Invalid questions payload received.");
          }
        }
      } catch (err) {
        console.error("Error fetching questions:", err);
        if (isMounted) {
          setError("Network error while loading questions. Please check your connection.");
        }
      } finally {
        if (isMounted) setLoading(false);
      }
    }

    fetchQuestions();

    return () => {
      isMounted = false;
    };
  }, [courseSlug, quizSlug, token, initialData]);

  const course = data?.course || {};
  const quiz = data?.quiz || {};
  const questions = useMemo(() => {
    return Array.isArray(data?.questions) ? data.questions : [];
  }, [data]);

  const totalQuestions = questions.length;
  const currentQuestion = questions[currentIndex] || null;

  // Initialize timer and attempt storage
  const storageKey = `scholaracad_quiz_attempt_${quizSlug}`;
  useEffect(() => {
    if (!quiz?.duration_minutes && quiz?.duration_minutes !== 0) return;
    
    // Check for saved attempt in localStorage
    try {
      const saved = localStorage.getItem(storageKey);
      if (saved) {
        const parsed = JSON.parse(saved);
        if (parsed.selectedAnswers) setSelectedAnswers(parsed.selectedAnswers);
        if (parsed.flaggedQuestions) setFlaggedQuestions(parsed.flaggedQuestions);
        if (parsed.currentIndex !== undefined) setCurrentIndex(parsed.currentIndex);
        if (parsed.timeRemaining !== undefined && parsed.timeRemaining > 0) {
          setTimeRemaining(parsed.timeRemaining);
        } else {
          setTimeRemaining((quiz.duration_minutes || 60) * 60);
        }
        if (parsed.timeSpentSeconds !== undefined) {
          setTimeSpentSeconds(parsed.timeSpentSeconds);
        }
        return;
      }
    } catch (e) {
      console.warn("Storage error", e);
    }

    // Default timer initialization
    const initialSeconds = (Number(quiz.duration_minutes) || 60) * 60;
    setTimeRemaining(initialSeconds);
  }, [quiz?.duration_minutes, storageKey]);

  // Timer interval countdown
  useEffect(() => {
    if (isSubmitted || timeRemaining === null || timeRemaining <= 0) return;

    const timer = setInterval(() => {
      setTimeRemaining((prev) => {
        if (prev <= 1) {
          clearInterval(timer);
          handleFinalSubmit(); // Auto-submit when time expires
          return 0;
        }
        return prev - 1;
      });
      setTimeSpentSeconds((prev) => prev + 1);
    }, 1000);

    return () => clearInterval(timer);
  }, [isSubmitted, timeRemaining]);

  // Save attempt to localStorage on change
  useEffect(() => {
    if (isSubmitted || totalQuestions === 0) return;
    try {
      localStorage.setItem(
        storageKey,
        JSON.stringify({
          selectedAnswers,
          flaggedQuestions,
          currentIndex,
          timeRemaining,
          timeSpentSeconds,
        })
      );
    } catch (e) {
      // ignore quota errors
    }
  }, [selectedAnswers, flaggedQuestions, currentIndex, timeRemaining, timeSpentSeconds, isSubmitted, storageKey, totalQuestions]);

  const courseDisplayName = useMemo(() => {
    if (course?.name) return course.name;
    if (courseSlug) {
      return courseSlug
        .split("-")
        .map((w) => w.charAt(0).toUpperCase() + w.slice(1))
        .join(" ");
    }
    return "Course";
  }, [course, courseSlug]);

  // Answer selection handler
  const handleSelectOption = (questionId, optionId) => {
    if (isSubmitted) return;
    setSelectedAnswers((prev) => ({
      ...prev,
      [questionId]: optionId,
    }));
  };

  // Flag toggle handler
  const handleToggleFlag = (questionId) => {
    setFlaggedQuestions((prev) => ({
      ...prev,
      [questionId]: !prev[questionId],
    }));
  };

  const handleNext = () => {
    if (currentIndex < totalQuestions - 1) {
      setCurrentIndex((prev) => prev + 1);
    }
  };

  const handlePrev = () => {
    if (currentIndex > 0) {
      setCurrentIndex((prev) => prev - 1);
    }
  };

  const handleJumpToQuestion = (index) => {
    if (index >= 0 && index < totalQuestions) {
      setCurrentIndex(index);
    }
  };

  // Final Submit Handler
  const handleFinalSubmit = () => {
    setIsSubmitted(true);
    setShowSubmitModal(false);
    try {
      localStorage.removeItem(storageKey);
    } catch (e) {
      // ignore
    }
  };

  // Retake Exam Handler
  const handleRetake = () => {
    setSelectedAnswers({});
    setFlaggedQuestions({});
    setCurrentIndex(0);
    setIsSubmitted(false);
    setShowSubmitModal(false);
    setTimeSpentSeconds(0);
    setTimeRemaining((Number(quiz.duration_minutes) || 60) * 60);
    try {
      localStorage.removeItem(storageKey);
    } catch (e) {
      // ignore
    }
  };

  // Timer formatting (HH:MM:SS or MM:SS)
  const formatTimer = (seconds) => {
    if (seconds === null || seconds === undefined) return "--:--";
    const hrs = Math.floor(seconds / 3600);
    const mins = Math.floor((seconds % 3600) / 60);
    const secs = seconds % 60;
    if (hrs > 0) {
      return `${String(hrs).padStart(2, "0")}:${String(mins).padStart(2, "0")}:${String(secs).padStart(2, "0")}`;
    }
    return `${String(mins).padStart(2, "0")}:${String(secs).padStart(2, "0")}`;
  };

  const progressPercentage = totalQuestions > 0
    ? Math.round(((currentIndex + 1) / totalQuestions) * 100)
    : 0;

  const answeredCount = Object.keys(selectedAnswers).length;
  const unansweredCount = totalQuestions - answeredCount;
  const flaggedCount = Object.values(flaggedQuestions).filter(Boolean).length;

  const passingScore = Number(quiz?.passing_score) || 70;
  // Estimated percentage based on answered ratio or completion
  const completionPercentage = totalQuestions > 0 ? Math.round((answeredCount / totalQuestions) * 100) : 0;
  const isPassed = completionPercentage >= passingScore;

  return (
    <>
      <DynamicSEO
        title={`${quiz?.title || "Exam Simulator"} — ${isSubmitted ? "Result Summary" : "Questions"} | ScholarAcad`}
        description={`Interactive question screen for ${quiz?.title || "Quiz"} - ${courseDisplayName}`}
        canonicalUrl={getCleanCanonicalUrl(`/${courseSlug}/quizzes/${quizSlug}/questions`)}
        robots="noindex, nofollow"
      />

      <Navbar />

      <main className="min-h-screen bg-[#f3f4f8] pb-16">
        {/* Top Sticky Header */}
        <header className="sticky top-0 z-30 bg-gradient-to-r from-[#491689] via-[#6520b8] to-[#7f26d9] text-white shadow-md">
          <div className="max-w-6xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between gap-4">
            <div className="flex items-center gap-3 min-w-0">
              <Link
                href={`/${courseSlug}/quizzes/${quizSlug}`}
                className="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-white transition text-xs font-semibold flex items-center gap-1.5 shrink-0"
                title="Back to Quiz Details"
              >
                <FaArrowLeft className="text-xs" />
                <span className="hidden sm:inline">Details</span>
              </Link>

              <div className="min-w-0">
                <h1 className="text-sm sm:text-base font-bold text-white truncate">
                  {quiz?.title || "Exam Simulator"}
                </h1>
                <p className="text-[11px] text-purple-200 truncate">
                  {courseDisplayName}
                </p>
              </div>
            </div>

            {/* Timer & Submit Bar */}
            {!isSubmitted && totalQuestions > 0 && (
              <div className="flex items-center gap-3 sm:gap-4 shrink-0">
                {/* Live Countdown Timer */}
                <div
                  className={`flex items-center gap-2 px-3.5 py-1.5 rounded-xl border text-xs sm:text-sm font-black font-mono shadow-sm transition ${
                    timeRemaining !== null && timeRemaining < 300
                      ? "bg-red-500/20 border-red-400 text-amber-300 animate-pulse"
                      : "bg-white/15 border-white/20 text-white"
                  }`}
                  title="Time Remaining"
                >
                  <MdOutlineTimer className="text-base text-amber-300" />
                  <span>{formatTimer(timeRemaining)}</span>
                </div>

                {/* Submit Exam Button */}
                <button
                  type="button"
                  onClick={() => setShowSubmitModal(true)}
                  className="px-4 py-1.5 sm:py-2 rounded-xl text-xs sm:text-sm font-bold bg-amber-400 hover:bg-amber-300 text-slate-900 shadow-md flex items-center gap-1.5 transition cursor-pointer"
                >
                  <MdOutlineSend className="text-sm" />
                  <span>Submit Exam</span>
                </button>
              </div>
            )}
          </div>

          {/* Progress Bar */}
          {!isSubmitted && totalQuestions > 0 && (
            <div className="w-full bg-black/20 h-1.5">
              <div
                className="bg-amber-400 h-1.5 transition-all duration-300 ease-out"
                style={{ width: `${progressPercentage}%` }}
              />
            </div>
          )}
        </header>

        {/* Content Section */}
        <section className="max-w-4xl mx-auto px-4 sm:px-6 pt-6 sm:pt-8">
          {/* Loading State */}
          {loading && (
            <div className="bg-white rounded-3xl p-16 shadow-sm border border-slate-200 flex flex-col items-center justify-center space-y-4 my-8">
              <Loader />
              <p className="text-slate-500 text-sm font-medium animate-pulse">
                Loading exam questions from question bank...
              </p>
            </div>
          )}

          {/* Error State */}
          {!loading && error && (
            <div className="bg-white rounded-3xl p-12 text-center border border-red-200 shadow-sm max-w-lg mx-auto my-8">
              <div className="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto text-3xl mb-4">
                <FaExclamationTriangle />
              </div>
              <h2 className="text-xl font-bold text-slate-900 mb-2">
                Unable to Load Questions
              </h2>
              <p className="text-sm text-slate-500 mb-6">{error}</p>
              <Link
                href={`/${active_country || "in"}/${courseSlug}/quizzes/${quizSlug}`}
                className="inline-flex items-center gap-2 px-5 py-2.5 bg-[#6520b8] text-white rounded-xl text-xs font-bold hover:bg-[#53169c] transition shadow-sm"
              >
                <FaArrowLeft className="text-xs" />
                <span>Return to Quiz Details</span>
              </Link>
            </div>
          )}

          {/* Empty State */}
          {!loading && !error && totalQuestions === 0 && (
            <div className="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm max-w-lg mx-auto my-8">
              <div className="w-16 h-16 bg-purple-50 text-[#6520b8] rounded-2xl flex items-center justify-center mx-auto text-3xl mb-4">
                <MdQuiz />
              </div>
              <h2 className="text-xl font-bold text-slate-900 mb-2">
                No Questions Available
              </h2>
              <p className="text-sm text-slate-500 mb-6">
                This quiz currently does not have any published questions. Please check back soon.
              </p>
              <Link
                href={`/${courseSlug}/quizzes/${quizSlug}`}
                className="inline-flex items-center gap-2 px-5 py-2.5 bg-[#6520b8] text-white rounded-xl text-xs font-bold hover:bg-[#53169c] transition shadow-sm"
              >
                <FaArrowLeft className="text-xs" />
                <span>Return to Quiz Details</span>
              </Link>
            </div>
          )}

          {/* RESULT / SUBMITTED SUMMARY VIEW */}
          {!loading && !error && isSubmitted && (
            <div className="space-y-6">
              <div className="bg-white rounded-3xl shadow-sm border border-slate-200/90 p-6 sm:p-10 text-center space-y-6">
                {/* Result Status Icon */}
                <div
                  className={`w-20 h-20 rounded-3xl flex items-center justify-center mx-auto text-4xl shadow-inner ${
                    isPassed
                      ? "bg-emerald-50 text-emerald-600 border border-emerald-200"
                      : "bg-purple-50 text-[#6520b8] border border-purple-200"
                  }`}
                >
                  <FaTrophy />
                </div>

                <div className="space-y-2">
                  <span
                    className={`inline-block px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wider ${
                      isPassed
                        ? "bg-emerald-100 text-emerald-800"
                        : "bg-amber-100 text-amber-800"
                    }`}
                  >
                    {isPassed ? "Exam Successfully Completed" : "Attempt Completed"}
                  </span>
                  <h2 className="text-2xl sm:text-3xl font-black text-slate-900">
                    {quiz?.title}
                  </h2>
                  <p className="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                    Your answers have been recorded. Here is your session summary and response breakdown.
                  </p>
                </div>

                {/* Score & Completion Metrics Grid */}
                <div className="grid grid-cols-2 sm:grid-cols-4 gap-3.5 sm:gap-4 max-w-2xl mx-auto pt-2">
                  <div className="bg-slate-50 rounded-2xl p-4 border border-slate-200/80">
                    <span className="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">
                      Answered
                    </span>
                    <span className="text-xl sm:text-2xl font-black text-[#6520b8]">
                      {answeredCount} / {totalQuestions}
                    </span>
                  </div>

                  <div className="bg-slate-50 rounded-2xl p-4 border border-slate-200/80">
                    <span className="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">
                      Completion
                    </span>
                    <span className="text-xl sm:text-2xl font-black text-slate-800">
                      {completionPercentage}%
                    </span>
                  </div>

                  <div className="bg-slate-50 rounded-2xl p-4 border border-slate-200/80">
                    <span className="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">
                      Time Spent
                    </span>
                    <span className="text-xl sm:text-2xl font-black text-slate-800">
                      {formatTimer(timeSpentSeconds)}
                    </span>
                  </div>

                  <div className="bg-slate-50 rounded-2xl p-4 border border-slate-200/80">
                    <span className="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">
                      Target Score
                    </span>
                    <span className="text-xl sm:text-2xl font-black text-slate-800">
                      {passingScore}%
                    </span>
                  </div>
                </div>

                {/* Action Buttons on Result Screen */}
                <div className="pt-6 border-t border-slate-100 flex flex-col sm:flex-row gap-3.5 justify-center max-w-md mx-auto">
                  <button
                    type="button"
                    onClick={handleRetake}
                    className="flex-1 py-3 px-5 rounded-xl text-xs sm:text-sm font-bold bg-[#6520b8] hover:bg-[#53169c] text-white shadow-md flex items-center justify-center gap-2 transition cursor-pointer"
                  >
                    <FaRedo className="text-xs" />
                    <span>Retake Exam</span>
                  </button>

                  <Link
                    href={`/${courseSlug}/quizzes`}
                    className="flex-1 py-3 px-5 rounded-xl text-xs sm:text-sm font-bold bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 shadow-sm flex items-center justify-center gap-2 transition cursor-pointer"
                  >
                    <span>All Quizzes</span>
                  </Link>
                </div>
              </div>
            </div>
          )}

          {/* ACTIVE QUESTIONS SCREEN (1x1 Focus View) */}
          {!loading && !error && !isSubmitted && currentQuestion && (
            <div className="space-y-6">
              <div className="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200/90 overflow-hidden">
                {/* Question Card Header (Order No, Type, Flag Button) */}
                <div className="bg-slate-50/90 border-b border-slate-100 px-6 py-4 flex flex-wrap items-center justify-between gap-3">
                  <div className="flex items-center gap-2">
                    <span className="bg-[#6520b8] text-white text-xs font-black px-3 py-1 rounded-lg">
                      Question #{currentQuestion?.order_no || currentIndex + 1}
                    </span>
                    <span className="text-xs font-semibold text-slate-500 bg-white border border-slate-200 px-2.5 py-1 rounded-lg">
                      {currentQuestion?.type ? currentQuestion.type.replace(/_/g, " ").toUpperCase() : "SINGLE CHOICE"}
                    </span>
                  </div>

                  {/* Flag / Review Toggle Button */}
                  <div className="flex items-center gap-3">
                    <button
                      type="button"
                      onClick={() => handleToggleFlag(currentQuestion.id)}
                      className={`px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5 transition cursor-pointer ${
                        flaggedQuestions[currentQuestion.id]
                          ? "bg-amber-100 text-amber-800 border border-amber-300"
                          : "bg-white hover:bg-slate-100 text-slate-600 border border-slate-200"
                      }`}
                      title="Flag this question for review"
                    >
                      {flaggedQuestions[currentQuestion.id] ? (
                        <>
                          <FaFlag className="text-amber-600 text-xs" />
                          <span>Flagged</span>
                        </>
                      ) : (
                        <>
                          <FaRegFlag className="text-slate-400 text-xs" />
                          <span>Flag</span>
                        </>
                      )}
                    </button>

                    <span className="text-xs font-semibold text-slate-400">
                      {currentQuestion?.marks || 1} Mark{currentQuestion?.marks > 1 ? "s" : ""}
                    </span>
                  </div>
                </div>

                <div className="p-6 sm:p-8 space-y-6">
                  {/* Question Text */}
                  <div className="text-base sm:text-lg font-bold text-slate-900 leading-relaxed">
                    {currentQuestion?.question_text}
                  </div>

                  {/* Question Image (if any) */}
                  {currentQuestion?.image_url && (
                    <div className="rounded-2xl overflow-hidden border border-slate-200 max-w-xl">
                      <img
                        src={currentQuestion.image_url}
                        alt={`Question ${currentIndex + 1} Visual`}
                        className="w-full h-auto object-contain bg-slate-50"
                      />
                    </div>
                  )}

                  {/* Options List */}
                  <div className="space-y-3 pt-2">
                    {Array.isArray(currentQuestion?.options) &&
                      currentQuestion.options.map((option) => {
                        const isSelected =
                          selectedAnswers[currentQuestion.id] === option.id;

                        return (
                          <button
                            key={option.id}
                            type="button"
                            onClick={() =>
                              handleSelectOption(currentQuestion.id, option.id)
                            }
                            className={`w-full text-left p-4 rounded-2xl border-2 transition-all duration-200 flex items-start gap-3.5 cursor-pointer ${
                              isSelected
                                ? "border-[#6520b8] bg-purple-50/70 shadow-sm ring-1 ring-[#6520b8]"
                                : "border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50/60"
                            }`}
                          >
                            {/* Option Label Badge (A, B, C, D) */}
                            <span
                              className={`w-8 h-8 rounded-xl font-bold text-xs flex items-center justify-center shrink-0 transition-colors ${
                                isSelected
                                  ? "bg-[#6520b8] text-white"
                                  : "bg-slate-100 text-slate-700 border border-slate-200"
                              }`}
                            >
                              {option.option_label || ""}
                            </span>

                            {/* Option Text */}
                            <div className="flex-1 pt-1 text-xs sm:text-sm font-semibold text-slate-800 leading-relaxed">
                              {option.option_text}

                              {/* Option Image (if any) */}
                              {option.image_url && (
                                <div className="mt-2 rounded-xl overflow-hidden border border-slate-200 max-w-sm">
                                  <img
                                    src={option.image_url}
                                    alt={`Option ${option.option_label}`}
                                    className="w-full h-auto object-contain"
                                  />
                                </div>
                              )}
                            </div>

                            {/* Radio Check Circle */}
                            <div className="pt-1 text-lg shrink-0">
                              {isSelected ? (
                                <FaCheckCircle className="text-[#6520b8]" />
                              ) : (
                                <FaRegCircle className="text-slate-300" />
                              )}
                            </div>
                          </button>
                        );
                      })}
                  </div>
                </div>

                {/* Bottom Navigation Bar */}
                <div className="bg-slate-50 border-t border-slate-100 px-6 py-4 flex items-center justify-between gap-3">
                  <button
                    type="button"
                    onClick={handlePrev}
                    disabled={currentIndex === 0}
                    className={`px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 transition ${
                      currentIndex === 0
                        ? "bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200"
                        : "bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 shadow-sm cursor-pointer"
                    }`}
                  >
                    <FaArrowLeft className="text-xs" />
                    <span>Previous</span>
                  </button>

                  <div className="text-xs font-semibold text-slate-500 hidden sm:block">
                    Question {currentIndex + 1} of {totalQuestions}
                  </div>

                  {currentIndex < totalQuestions - 1 ? (
                    <button
                      type="button"
                      onClick={handleNext}
                      className="px-6 py-2.5 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 bg-[#6520b8] hover:bg-[#53169c] text-white shadow-md shadow-purple-200 transition cursor-pointer"
                    >
                      <span>Next</span>
                      <FaArrowRight className="text-xs" />
                    </button>
                  ) : (
                    <button
                      type="button"
                      onClick={() => setShowSubmitModal(true)}
                      className="px-6 py-2.5 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 bg-amber-400 hover:bg-amber-300 text-slate-900 shadow-md transition cursor-pointer"
                    >
                      <MdOutlineSend className="text-sm" />
                      <span>Finish & Submit</span>
                    </button>
                  )}
                </div>
              </div>

              {/* Question Navigation Palette / Quick Jump Grid */}
              {totalQuestions > 1 && (
                <div className="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                  <div className="flex flex-wrap items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                    <span className="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                      <MdFormatListNumbered className="text-sm text-[#6520b8]" />
                      <span>Question Palette ({totalQuestions} Total)</span>
                    </span>

                    {/* Legend */}
                    <div className="flex items-center gap-3 text-[11px] font-semibold text-slate-500">
                      <span className="flex items-center gap-1">
                        <span className="w-2.5 h-2.5 rounded-full bg-[#6520b8]"></span>
                        Current
                      </span>
                      <span className="flex items-center gap-1">
                        <span className="w-2.5 h-2.5 rounded-full bg-purple-300"></span>
                        Answered ({answeredCount})
                      </span>
                      <span className="flex items-center gap-1">
                        <span className="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        Flagged ({flaggedCount})
                      </span>
                    </div>
                  </div>

                  <div className="flex flex-wrap gap-2 max-h-48 overflow-y-auto p-1">
                    {questions.map((q, idx) => {
                      const isCurrent = idx === currentIndex;
                      const isAnswered = selectedAnswers[q.id] !== undefined;
                      const isFlagged = Boolean(flaggedQuestions[q.id]);

                      return (
                        <button
                          key={q.id || idx}
                          type="button"
                          onClick={() => handleJumpToQuestion(idx)}
                          className={`relative w-9 h-9 rounded-xl text-xs font-bold flex items-center justify-center transition cursor-pointer ${
                            isCurrent
                              ? "bg-[#6520b8] text-white ring-2 ring-[#6520b8] ring-offset-1"
                              : isFlagged
                              ? "bg-amber-100 text-amber-900 border border-amber-300"
                              : isAnswered
                              ? "bg-purple-100 text-[#6520b8] border border-purple-300 font-extrabold"
                              : "bg-slate-100 hover:bg-slate-200 text-slate-600 border border-slate-200"
                          }`}
                        >
                          {idx + 1}
                          {isFlagged && (
                            <span className="absolute -top-1 -right-1 w-2.5 h-2.5 bg-amber-500 rounded-full"></span>
                          )}
                        </button>
                      );
                    })}
                  </div>
                </div>
              )}
            </div>
          )}
        </section>
      </main>

      {/* CONFIRMATION SUBMIT MODAL */}
      {showSubmitModal && (
        <div className="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
          <div className="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-200 space-y-6 animate-in fade-in zoom-in-95 duration-200">
            <div className="flex items-center justify-between border-b border-slate-100 pb-3">
              <h3 className="text-lg font-bold text-slate-900">
                Confirm Exam Submission
              </h3>
              <button
                type="button"
                onClick={() => setShowSubmitModal(false)}
                className="text-slate-400 hover:text-slate-600 text-xl cursor-pointer"
              >
                <IoCloseOutline />
              </button>
            </div>

            <p className="text-xs sm:text-sm text-slate-600">
              Are you sure you want to finish and submit your exam? Please check your progress summary below before submitting:
            </p>

            {/* Modal Stats Overview */}
            <div className="grid grid-cols-2 gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-200/80 text-xs">
              <div className="flex items-center justify-between">
                <span className="text-slate-500">Total Questions:</span>
                <span className="font-bold text-slate-800">{totalQuestions}</span>
              </div>
              <div className="flex items-center justify-between">
                <span className="text-slate-500">Answered:</span>
                <span className="font-bold text-emerald-600">{answeredCount}</span>
              </div>
              <div className="flex items-center justify-between">
                <span className="text-slate-500">Unanswered:</span>
                <span className="font-bold text-red-500">{unansweredCount}</span>
              </div>
              <div className="flex items-center justify-between">
                <span className="text-slate-500">Flagged:</span>
                <span className="font-bold text-amber-600">{flaggedCount}</span>
              </div>
            </div>

            {/* Modal Actions */}
            <div className="flex gap-3 pt-2">
              <button
                type="button"
                onClick={() => setShowSubmitModal(false)}
                className="flex-1 py-3 rounded-xl text-xs sm:text-sm font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition cursor-pointer"
              >
                Continue Test
              </button>

              <button
                type="button"
                onClick={handleFinalSubmit}
                className="flex-1 py-3 rounded-xl text-xs sm:text-sm font-bold bg-[#6520b8] hover:bg-[#53169c] text-white shadow-md shadow-purple-200 transition cursor-pointer"
              >
                Confirm & Submit
              </button>
            </div>
          </div>
        </div>
      )}

      <Footer />
    </>
  );
}
