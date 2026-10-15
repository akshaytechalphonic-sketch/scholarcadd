import React from "react";
import { useRouter } from "next/router";
import DedicatedQuizzesPage from "@/Components/DedicatedQuizzesPage";
import { API_BASE_URL } from "../../../apiconfig";

export default function CourseQuizzesPage({ initialQuizzes, courseData, courseSlug }) {
  const router = useRouter();
  const slug = courseSlug || router.query.courseSlug;

  return (
    <DedicatedQuizzesPage
      courseSlug={slug}
      initialQuizzes={initialQuizzes}
      courseData={courseData}
      url_title={slug}
    />
  );
}

export async function getServerSideProps({ req, params }) {
  const token = req.cookies?.access_token || null;
  const courseSlug = params?.courseSlug || "";

  try {
    const res = await fetch(`${API_BASE_URL}/api/courses/${courseSlug}/quizzes`, {
      method: "GET",
      headers: {
        Accept: "application/json",
        ...(token ? { Authorization: `Bearer ${token}` } : {}),
      },
    });

    if (res.status === 404 || !res.ok) {
      return { notFound: true };
    }

    const data = await res.json();
    if (!data?.status || !data?.data || (Array.isArray(data.data) && data.data.length === 0)) {
      return { notFound: true };
    }

    return {
      props: {
        courseSlug,
        initialQuizzes: Array.isArray(data?.data) ? data.data : [],
        courseData: data?.course || null,
      },
    };
  } catch (e) {
    return { notFound: true };
  }
}
