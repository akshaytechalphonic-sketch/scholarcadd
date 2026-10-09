import {
  IoBookOutline,
  IoCheckmarkCircleOutline,
  IoHomeOutline,
} from "react-icons/io5";
import Link from "next/link";

export default function Success() {
  return (
    <div className="flex items-center justify-center min-h-screen bg-green-50 px-4">
      <div className="text-center bg-white border border-green-200 rounded-3xl p-8 md:max-w-lg w-full ">
        <div className="flex justify-center mb-6 ">
          <IoCheckmarkCircleOutline className="text-green-600 text-7xl" />
        </div>
        <h1 className="md:text-3xl text-2xl font-bold text-green-700 mb-3">
          Payment Successful!
        </h1>
        <h2 className="text-lg font-semibold text-gray-800 my-2">
          Enrollment Confirmed
        </h2>
        <p className="text-gray-600 text-base my-2">
          Our team will connect with you soon.
        </p>
        <div className="flex flex-col sm:flex-row items-center justify-center gap-4 mt-4">
          <Link
            href="/"
            className="flex items-center gap-2 bg-[#2E318D] hover:bg-[#1f236a] transition text-white px-6 py-2 rounded-full text-sm"
          >
            <IoHomeOutline className="text-lg" />
            Go Back Home
          </Link>
          <Link
            href="/all-courses"
            className="flex items-center gap-2 text-sm bg-[#178bbd] hover:bg-[#0f6e95] transition text-white px-6 py-2 rounded-full"
          >
            <IoBookOutline className="text-lg" />
            Explore Courses
          </Link>
        </div>
      </div>
    </div>
  );
}
