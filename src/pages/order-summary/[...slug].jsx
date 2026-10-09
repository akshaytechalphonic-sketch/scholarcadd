import Footer from "@/Components/Footer";
import Navbar from "@/Components/Navbar";
import { useState, useEffect, useMemo } from "react";
import {
  IoIosAdd,
  IoIosTimer,
  IoMdCheckmarkCircleOutline,
} from "react-icons/io";
import { SlCalender } from "react-icons/sl";
import {
  IoBookOutline,
  IoCheckmarkCircle,
  IoCheckmarkCircleOutline,
  IoCloseCircle,
  IoHomeOutline,
  IoTimeOutline,
} from "react-icons/io5";
import { BsCartDash, BsFilePdf, BsFillCartDashFill } from "react-icons/bs";
import { useCheckout } from "@/context/CheckoutContext";
import { LuMoveLeft, LuMoveRight } from "react-icons/lu";
import {
  FiArrowLeft,
  FiBookOpen,
  FiCheckCircle,
  FiChevronDown,
  FiChevronUp,
  FiGlobe,
  FiHash,
  FiHelpCircle,
  FiLock,
  FiRefreshCcw,
} from "react-icons/fi";
import {
  FiAlertCircle,
  FiMail,
  FiMessageCircle,
  FiPrinter,
} from "react-icons/fi";
import { GrFormSubtract } from "react-icons/gr";
import Link from "next/link";
import { SiRazorpay } from "react-icons/si";
import { FaUniversity } from "react-icons/fa";
import { useAuth } from "@/context/AuthContext";
import toast from "react-hot-toast";
import { API_BASE_URL, APIENDPOINTS } from "../../../apiconfig";
import { HiOutlineCalendarDateRange } from "react-icons/hi2";
import { useRouter } from "next/router";
import { MdOutlineLocalOffer } from "react-icons/md";
import Script from "next/script";
import FormModal from "@/Components/FormModal";
export default function Ordersummary() {
  const router = useRouter();
  const { token } = useAuth();
  const { countryLists } = useAuth();
  const [paymentMethod, setPaymentMethod] = useState("");
  const methodBox =
    "rounded-2xl p-5 cursor-pointer backdrop-blur-md shadow-md transition-all border bg-white/50";
  const { checkoutData, setCheckoutData, updateMultipleCourses, removeCourse } =
    useCheckout();
  const [courses, setCourses] = useState(checkoutData || []);
  console.log("courses----->", courses);
  // console.log("courses?.course_schedule_id",courses[0]?.course_schedule_id);

  useEffect(() => {
    setCourses(checkoutData);
  }, [checkoutData]);

  const [openStep, setOpenStep] = useState(1);
  const toggleStep = (step) => {
    setOpenStep(openStep === step ? null : step);
  };

  const capitalizeFirst = (str) =>
  str ? str.charAt(0).toUpperCase() + str.slice(1) : "NA";

  const [showAlternate, setShowAlternate] = useState(false);
  const [userLocation, setUserLocation] = useState(null);
  useEffect(() => {
    const saved = sessionStorage.getItem("userLocation");
    if (saved) {
      try {
        const parsed = JSON.parse(saved);
        setUserLocation(parsed);
      } catch (e) {
        console.error(" Failed to parse userLocation JSON", e);
      }
    }
  }, []);

  // console.log("userLocation",userLocation?.country);

  const active_country = useMemo(() => {
    return userLocation?.raw?.country.toLowerCase() || "in";
  }, [userLocation]);

  useEffect(() => {
    if (!active_country || countryLists.length === 0) return;
    const matched = countryLists.find(
      (item) => item.iso2?.toLowerCase() === active_country.toLowerCase(),
    );
    if (matched) {
      setFormData((prev) => ({
        ...prev,
        country_code: matched.phonecode.toString(),
        alternate_country_code: matched.phonecode.toString(),
      }));
    }
  }, [active_country, countryLists]);

  const [coursePlan, setCoursePlan] = useState([]);
  // console.log("coursePlan", coursePlan);
  const [selectedPlan, setSelectedPlan] = useState(null);
  useEffect(() => {
    if (!selectedPlan && coursePlan?.length) {
      const basicPlan = coursePlan.find((plan) => plan.isBasic === "yes");
      if (basicPlan) {
        setSelectedPlan(basicPlan);
      }
    }
  }, [coursePlan, selectedPlan]);
  useEffect(() => {
    if (!token) return;
    const Fetchcourseplans = async () => {
      try {
        const res = await fetch(`${API_BASE_URL}${APIENDPOINTS.COURSE_PLANS}`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
          },
          body: JSON.stringify({
            course_id: courses[0]?.url_title,
            country_id: courses[0]?.active_country || active_country || "USD",
          }),
        });
        if (!res.ok) {
          console.warn("HTTP error! Status:", res.status);
          return;
        }
        const data = await res.json();
        // console.log("Courseplan Data:", data?.data);
        if (data?.data?.length > 0) {
          setCoursePlan(data.data);
        }
      } catch (err) {
        console.error("Error fetching Courseplan:", err);
      }
    };
    Fetchcourseplans();
  }, [token]);

  const [promoCode, setPromoCode] = useState("");
  const [promoData, setPromoData] = useState(null);
  // console.log("promoData", promoData);
  const verifyPromoCode = async () => {
    if (!promoCode.trim()) {
      toast.error("Please enter a promo code");
      return;
    }

    try {
      setLoading(true);

      const res = await fetch(`${API_BASE_URL}${APIENDPOINTS.PROMO_VERIFY}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({
          promocode: promoCode.trim(),
          course_id: courses[0]?.course_id || null,
        }),
      });

      if (!res.ok) {
        toast.error("Invalid promo code");
        return;
      }
      const data = await res.json();
      // console.log("Promo Verify data:", data?.data);
      toast.success("Promo code applied successfully");
      if (data?.data) {
        setPromoData(data?.data);
      }
    } catch (error) {
      console.error("Promo verify error:", error);
      toast.error("Something went wrong. Please try again.");
    } finally {
      setLoading(false);
    }
  };

  const [formData, setFormData] = useState({
    enrollType: "myself",
    first_name: "",
    last_name: "",
    country_code: "",
    phone: "",
    email: "",
    alternate_country_code: "",
    alternate_contact: "",
    alternate_email: "",
    company_name: "",
  });

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
    setErrors((prev) => ({ ...prev, [name]: "" }));
  };

  const [errors, setErrors] = useState({});
  const validateForm = () => {
    const errors = {};

    if (!formData.first_name.trim()) {
      errors.first_name = "First name is required";
    }

    if (!formData.last_name.trim()) {
      errors.last_name = "Last name is required";
    }

    if (!formData.phone.trim()) {
      errors.phone = "Phone number is required";
    } else if (!/^[0-9]{10}$/.test(formData.phone)) {
      errors.phone = "Phone number must be 10 digits";
    }

    if (!formData.email.trim()) {
      errors.email = "Email is required";
    } else if (!/^\S+@\S+\.\S+$/.test(formData.email)) {
      errors.email = "Invalid email address";
    }
    //  Corporate-specific validation
    if (formData.enrollType === "corporate") {
      if (!formData.company_name?.trim()) {
        errors.company_name = "Company name is required";
      }
    }

    return errors;
  };

  const SESSION_DURATION = 20 * 60 * 1000;

  const [timeLeft, setTimeLeft] = useState(0);
  const [sessionActive, setSessionActive] = useState(false);
  const [enrollmentData, setEnrollmentData] = useState(null);
  // console.log("enrollmentData",enrollmentData);
  const handleSubmit = async () => {
    try {
      const errors = validateForm();
      if (Object.keys(errors).length > 0) {
        setErrors(errors);
        return;
      }
      const payload = {
        enroll_type: formData.enrollType,
        first_name: formData.first_name,
        last_name: formData.last_name,
        country_code: formData.country_code,
        phone: formData.phone,
        email: formData.email,
        company_name: formData.company_name,
        alternate_country_code: formData.alternate_country_code || null,
        alternate_contact: formData.alternate_contact || null,
        alternate_email: formData.alternate_email || null,
      };

      const res = await fetch(
        `${API_BASE_URL}${APIENDPOINTS.ENROLLMENTS_STORE}`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${token}`,
          },
          body: JSON.stringify(payload),
        },
      );
      if (!res.ok) {
        setLoading(false);
        toast.error("Something went wrong. Please try again.");
        return;
      }
      const data = await res.json();
      toast.success(
        "Enrollment saved successfully. Proceed with the next steps.",
      );
      // Save session in localStorage
      const sessionExpiry = Date.now() + SESSION_DURATION;
      const sessionInfo = {
        userId: data.id,
        expiresAt: sessionExpiry,
        enrollment: data,
      };
      localStorage.setItem("enrollmentSession", JSON.stringify(sessionInfo));
      // Update state
      setEnrollmentData(data);
      setTimeLeft(SESSION_DURATION / 1000);
      setSessionActive(true);
      setOpenStep(2);
      // Reset form
      setFormData({
        enrollType: "myself",
        first_name: "",
        last_name: "",
        country_code: "",
        phone: "",
        email: "",
        company_name: "",
        alternate_country_code: "",
        alternate_contact: "",
        alternate_email: "",
      });
    } catch (err) {
      toast.error("Submission failed. Try again later.");
    } finally {
      setLoading(false);
    }
  };

  // --- Check session on mount (for refresh or returning user) ---
  useEffect(() => {
    const sessionInfo = JSON.parse(localStorage.getItem("enrollmentSession"));
    // console.log("sessionInfo", sessionInfo?.enrollment?.data);
    if (!sessionInfo) return;
    const remainingTime = Math.floor(
      (sessionInfo.expiresAt - Date.now()) / 1000,
    );
    if (remainingTime > 0) {
      setEnrollmentData(sessionInfo.enrollment);
      setTimeLeft(remainingTime);
      setSessionActive(true);
      setOpenStep(2);
    } else {
      localStorage.removeItem("enrollmentSession");
    }
  }, []);

  // --- Countdown timer for session ---
  useEffect(() => {
    if (!sessionActive) return;

    const timer = setInterval(() => {
      setTimeLeft((prev) => {
        if (prev <= 1) {
          clearInterval(timer);
          setSessionActive(false);
          setEnrollmentData(null);
          setOpenStep(1);
          localStorage.removeItem("enrollmentSession");
          toast.error(
            "Session expired! Please fill the enrollment form again.",
          );
          return 0;
        }
        return prev - 1;
      });
    }, 1000);

    return () => clearInterval(timer);
  }, [sessionActive]);
  const now = new Date();

  if (!courses.length)
    return (
      <>
        <Navbar />
        <div className="py-20 flex flex-col items-center justify-center bg-white rounded-xl border border-gray-200 min-h-[90vh] ">
          <div className="p-5 bg-red-100 rounded-full  border">
            <BsCartDash className="text-4xl text-red-500 animate-bounce" />
          </div>
          <h2 className="text-xl font-semibold text-gray-700 mt-4">
            No Courses Selected
          </h2>
          <p className="text-gray-500 mt-1">
            Start adding courses to compare or enroll.
          </p>
          <Link
            href="/all-courses"
            className="text-[#2E318D] mt-3 flex gap-2 items-center font-semibold hover:underline"
          >
            <LuMoveLeft />
            Back to all Courses
          </Link>
        </div>
        <Footer />
      </>
    );

  const increment = (index) => {
    const newCourses = [...courses];
    newCourses[index].quantity = (newCourses[index].quantity || 1) + 1;
    setCourses(newCourses);
    updateMultipleCourses(newCourses);
  };

  const decrement = (index) => {
    const newCourses = [...courses];
    newCourses[index].quantity = Math.max(
      1,
      (newCourses[index].quantity || 1) - 1,
    );
    setCourses(newCourses);
    updateMultipleCourses(newCourses);
  };

  // const handleRemoveCourse = (schedule_id) => {
  //   removeCourse(schedule_id);
  // };

  const getTotals = (course, qty = 1, selectedPlan = null) => {
    //  CASE 1: Selected Plan
    if (selectedPlan) {
      const courseFee = Number(selectedPlan.course_fee) || 0;
      const discountPercent = Number(selectedPlan.discount) || 0;
      const taxPercent = Number(selectedPlan.tax) || 0;
      const discountAmount = (courseFee * discountPercent) / 100;
      const subtotal = courseFee - discountAmount;
      const taxAmount = (subtotal * taxPercent) / 100;
      // Base total (after discount + tax)
      let finalTotal = subtotal + taxAmount;
      // Apply promo ONLY when a plan is selected
      let promoDiscountAmount = 0;

      if (promoData && selectedPlan) {
        const promoDiscountPercent =
          Number(promoData?.discount_percentage) || 0;
        const promoDiscountAmount = (finalTotal * promoDiscountPercent) / 100;
        finalTotal = finalTotal - promoDiscountAmount;
      }
      return {
        totalOriginal: courseFee * qty,
        subtotal: subtotal * qty,
        taxAmount: taxAmount * qty,
        totalPrice: finalTotal * qty,
        totalSave: discountAmount * qty,
        discountPercent,
        taxPercent,
        isDiscountActive: discountPercent > 0,
        offerFor: course?.discount_offer_for || "No Offer",
      };
    }

    // CASE 2: Course logic if no plan selected
    const original = Number(course?.course_fees) || 0;
    const discountPercentAPI = Number(
      String(course?.discount_amt || "").replace("%", ""),
    );
    const taxPercent = Number(course?.tax) || 0;

    const hasEndDate = !!course?.discount_end_date;
    const isDiscountActive = hasEndDate
      ? new Date(course.discount_end_date + "T23:59:59") >= Date.now()
      : discountPercentAPI > 0;

    let finalUnitPrice = original;
    if (isDiscountActive && discountPercentAPI > 0) {
      finalUnitPrice = original - (original * discountPercentAPI) / 100;
    }

    const subtotal = finalUnitPrice * qty;
    const taxAmount = (subtotal * taxPercent) / 100;
    const totalOriginal = original * qty;
    let totalPrice = subtotal + taxAmount;
    let totalSave = totalOriginal - subtotal;
    let promoDiscountAmount = 0;
    if (promoData) {
      const promoPercent = Number(promoData?.discount_percentage) || 0;
      promoDiscountAmount = (totalPrice * promoPercent) / 100;
      totalPrice -= promoDiscountAmount;
      totalSave += promoDiscountAmount;
    }
    return {
      totalOriginal,
      subtotal,
      taxAmount,
      totalPrice,
      totalSave,
      discountPercent: discountPercentAPI,
      taxPercent,
      isDiscountActive,
      offerFor: course?.discount_offer_for || "No Offer",
    };
  };
  const grandTotal = courses.reduce((acc, course) => {
    const qty = course?.quantity || 1;
    const { totalPrice } = getTotals(course, qty, selectedPlan);

    return acc + totalPrice;
  }, 0);
  const totalQuantity = (courses || []).reduce((acc, course) => {
    return acc + (course?.quantity ?? 1);
  }, 0);

  // console.log("totalQuantity:", totalQuantity);

  const [loading, setLoading] = useState(false);
  const [paymentSuccess, setPaymentSuccess] = useState(false);
  const [isGeneratingInvoice, setIsGeneratingInvoice] = useState(false);
  const [invoiceData, setInvoiceData] = useState(null);
  const [invoiceError, setInvoiceError] = useState(null);

  const startRazorpayPayment = async () => {
    try {
      setLoading(true);
      const res = await fetch(
        `${API_BASE_URL}${APIENDPOINTS.CREATE_RAZORPAY_PAYMENT}`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
          },
          body: JSON.stringify({
            course_id: selectedPlan?.course_id,
            plan_id: selectedPlan?.id,
            currency: selectedPlan?.currency,
            quantity: totalQuantity,
            promo_code: promoData?.id,
            // amount: grandTotal,
          }),
        },
      );
      const data = await res.json();
      console.log("Create Order Response:", data);
      const order_id = data?.order_id;
      const amount = data?.amount;
      const key = data?.key;
      if (!order_id || !amount || !key) {
        console.error("Missing payment details", data);
        toast("Payment initialization failed: invalid server response");
        return;
      }
      const options = {
        key: key,
        amount: amount,
        currency: courses?.fee_currency || null,
        name: "Scholaracad",
        image:
          "https://scholaracad.com/assets/landingpage/scholaracad_favicon.svg",
        description: "Course Enrollment Payment",
        order_id: order_id,
        handler: async function (response) {
          try {
            const verifyRes = await fetch(
              `${API_BASE_URL}${APIENDPOINTS.VERIFY_RAZORPAY_PAYMENT}`,
              {
                method: "POST",
                headers: {
                  "Content-Type": "application/json",
                  Authorization: `Bearer ${token}`,
                },
                body: JSON.stringify({
                  razorpay_order_id: order_id,
                  razorpay_payment_id: response?.razorpay_payment_id,
                  razorpay_signature: response?.razorpay_signature,
                  // course_id: selectedPlan?.course_id,
                  plan_id: selectedPlan?.id,
                  currency: selectedPlan?.currency,
                  quantity: totalQuantity,
                  promo_code: promoData?.id,
                }),
              },
            );
            const paymentDate =
              now.getFullYear() +
              "-" +
              String(now.getMonth() + 1).padStart(2, "0") +
              "-" +
              String(now.getDate()).padStart(2, "0") +
              " " +
              String(now.getHours()).padStart(2, "0") +
              ":" +
              String(now.getMinutes()).padStart(2, "0") +
              ":" +
              String(now.getSeconds()).padStart(2, "0");
            // console.log("paymentDate",paymentDate);
            const verifyData = await verifyRes.json();
            // console.log("verifyData---->",verifyData);
            if (verifyRes.status === 200) {
              toast.success("Payment Successful!");
              setPaymentSuccess(true);
              const paymentStoreRes = await fetch(
                `${API_BASE_URL}${APIENDPOINTS.STORE_PAYMENTDATA}`,
                {
                  method: "POST",
                  headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`,
                  },
                  body: JSON.stringify({
                    enrollment_id: enrollmentData?.data?.id || null,
                    plan_id: selectedPlan?.id,
                    schedule_id: courses[0]?.course_schedule_id,
                    payment_id: response?.razorpay_payment_id || null,
                    currency: selectedPlan?.currency,
                    quantity: totalQuantity,
                    payment_status: true,
                    payment_date: paymentDate,
                    promo_code: promoData?.id || null,
                  }),
                },
              );
              const paymentStoreData = await paymentStoreRes.json();
              if (!paymentStoreRes.ok) {
                throw new Error(
                  paymentStoreData?.message || "Payment store failed",
                );
              }
              const paymentId = paymentStoreData?.data?.id;
              // Generate invoice
              setIsGeneratingInvoice(true);
              const invoiceRes = await fetch(
                `${API_BASE_URL}${APIENDPOINTS.DOWNLOAD_PAYMENT_INVOICE}`,
                {
                  method: "POST",
                  headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`,
                  },
                  body: JSON.stringify({
                    id: paymentId,
                  }),
                },
              );
              const invoiceDataRes = await invoiceRes.json();
              //  console.log("invoiceDataRes",invoiceDataRes);
              //  console.log("invoiceDataRes",invoiceDataRes?.pdf_url);

              if (!invoiceRes.ok) {
                setIsGeneratingInvoice(false);
                setInvoiceError(true);
                toast.error(
                  invoiceDataRes?.message || "Invoice generation failed",
                );
                return;
              }
              setInvoiceData(invoiceDataRes);
              setIsGeneratingInvoice(false);
              toast.success("Invoice generated successfully");
            } else {
              setInvoiceError(true);
              setIsGeneratingInvoice(false);
              toast.error("Payment successful, but invoice not generated");
            }
          } catch (error) {
            console.log("Verification Error:", error);
            // toast.error("Payment Verification Error!");
          }
        },
        theme: {
          color: "#882CFB",
        },
      };
      // Open Razorpay Popup
      const rzp = new window.Razorpay(options);
      rzp.open();
    } catch (err) {
      console.error("Start Payment Error:", err);
      toast("Error starting payment");
    } finally {
      setLoading(false);
    }
  };
  // const [countdown, setCountdown] = useState(20);
  // const [redirected, setRedirected] = useState(false);

  // useEffect(() => {
  //   if (countdown === 0) {
  //     window.location.href = "/";
  //     setRedirected(true);
  //     return;
  //   }
  //   const timer = setTimeout(() => {
  //     setCountdown(countdown - 1);
  //   }, 1000);

  //   return () => clearTimeout(timer);
  // }, [countdown]);

  if (paymentSuccess) {
    return (
      <>
        <div className="flex items-center justify-center min-h-screen bg-green-50 px-4">
          <div className="text-center bg-white border border-green-200 rounded-3xl p-8 md:max-w-lg w-full ">
            <div className="flex justify-center mb-6 ">
              <IoCheckmarkCircleOutline className="text-green-600 text-7xl" />
            </div>
            <h2 className="md:text-3xl text-2xl font-bold text-green-700 mb-3">
              Payment Successful!
            </h2>
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

              {isGeneratingInvoice && (
                <div className="flex items-center gap-2 text-sm text-gray-600">
                  <span className="invoiceloader"></span>
                  Generating invoice...
                </div>
              )}

              {!isGeneratingInvoice && invoiceData && (
                <button
                  onClick={() => window.open(invoiceData?.pdf_url, "_blank")}
                  className="px-6 py-2 flex items-center gap-2 bg-green-600 hover:bg-green-700 transition text-white rounded-full text-sm"
                >
                  <BsFilePdf className="text-lg" />
                  View Invoice
                </button>
              )}

              {!isGeneratingInvoice && !invoiceData && invoiceError && (
                <p className="text-sm text-yellow-700">
                  Invoice will be shared with you shortly.
                </p>
              )}
              {/* <Link
                href="/all-courses"
                className="flex items-center gap-2 text-sm bg-[#178bbd] hover:bg-[#0f6e95] transition text-white px-6 py-2 rounded-full"
              >
                <IoBookOutline className="text-lg" />
                Explore Courses
              </Link> */}
            </div>
          </div>
        </div>
      </>
    );
  }

  // const startCCAvenuePayment = async () => {
  //   try {
  //     const res = await fetch(
  //       `${API_BASE_URL}${APIENDPOINTS.CCAVENUE_PAYMENT}`,
  //       {
  //         method: "POST",
  //         headers: {
  //           "Content-Type": "application/json",
  //           Accept: "application/json",
  //           Authorization: `Bearer ${token}`,
  //         },
  //         body: JSON.stringify({
  //           course_id: courses?.url_title,
  //           amount: grandTotal,
  //         }),
  //       }
  //     );
  //     const data = await res.json();
  //     console.log("data", data);
  //     console.log("CCAvenue Pay Response:", data);
  //     if (!data?.encRequest || !data?.accessCode || !data?.paymentUrl) {
  //       console.error("Invalid CCAvenue response", data);
  //       return;
  //     }
  //     const form = document.createElement("form");
  //     form.method = "POST";
  //     form.action = data?.paymentUrl;
  //     form.innerHTML = `
  //       <input type="hidden" name="encRequest" value="${data?.encRequest}" />
  //       <input type="hidden" name="access_code" value="${data?.accessCode}" />
  //     `;
  //     document.body.appendChild(form);
  //     form.submit();
  //   } catch (error) {
  //     console.error("CCAvenue Payment Error:", error);
  //   }
  // };

  const startCCAvenuePayment = async () => {
    try {
       const paymentDate =
              now.getFullYear() +
              "-" +
              String(now.getMonth() + 1).padStart(2, "0") +
              "-" +
              String(now.getDate()).padStart(2, "0") +
              " " +
              String(now.getHours()).padStart(2, "0") +
              ":" +
              String(now.getMinutes()).padStart(2, "0") +
              ":" +
              String(now.getSeconds()).padStart(2, "0");
            // console.log("paymentDate",paymentDate);
      const res = await fetch(
        `${API_BASE_URL}${APIENDPOINTS.CCAVENUE_PAYMENT}`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
          },
          body: JSON.stringify({
            plan_id: selectedPlan?.id,
            quantity: totalQuantity,
            currency: selectedPlan?.currency,
            promo_code: promoData?.id,
            enrollment_id: enrollmentData?.data?.id,
            schedule_id: courses[0]?.course_schedule_id,
            payment_date: paymentDate,
            // course_id: selectedPlan?.course_id,
            // amount: grandTotal,
          }),
        },
      );
      const data = await res.json();
      // console.log("CCAvenue Pay Response:", data);
      if (!data?.encRequest || !data?.accessCode || !data?.paymentUrl) {
        toast.error("Invalid CCAvenue response");
        return;
      }
      const form = document.createElement("form");
      form.method = "POST";
      form.action = data?.paymentUrl;
      form.enctype = "application/x-www-form-urlencoded";
      form.innerHTML = `
        <input type="hidden" name="encRequest" value="${data?.encRequest}" />
        <input type="hidden" name="access_code" value="${data?.accessCode}" />
      `;
      document.body.appendChild(form);
      form.submit();
    } catch (error) {
      console.error("CCAvenue Payment Error:", error);
    }
  };

  const startPayment = async () => {
    if (!paymentMethod) {
      toast("Please select a payment method");
      return;
    }
    setLoading(true);
    try {
      if (paymentMethod === "razorpay") {
        await startRazorpayPayment(grandTotal);
      } else {
        await startCCAvenuePayment(grandTotal);
      }
    } catch (error) {
      console.error("Payment Error:", error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      <Script
        src="https://checkout.razorpay.com/v1/checkout.js"
        strategy="afterInteractive"
      />

      {/* <Navbar /> */}
      <nav className="w-full bg-white border-b border-gray-200">
        <div className="max-w-7xl mx-auto py-3 flex flex-col md:flex-row items-center justify-between">
          <div className="flex items-center gap-4">
            <div className="text-lg font-semibold text-gray-800">
              <Link href="/">
                <img
                  src="/assets/landingpage/scholaracad_icon_footer.svg"
                  alt="Scholor Acad Logo"
                  width={220}
                  height={220}
                  className="object-contain mt-1"
                />
              </Link>
            </div>
          </div>
          <div className="flex items-center gap-4 text-sm font-medium max-sm:mt-2">
            <button
              title="Back"
              onClick={() => window.history.back()}
              className="flex items-center gap-1 text-gray-600 hover:text-blue-600 transition cursor-pointer"
            >
              <FiArrowLeft className="text-sm " />
              <span>Back</span>
            </button>
            <span className="h-4 w-px bg-gray-300" />
            <div className="flex items-center gap-1 px-3 py-1 rounded-full bg-green-100 text-green-700">
              <FiLock className="text-sm" />
              <span>Secure Checkout</span>
            </div>
          </div>
        </div>
      </nav>

      <section className="bg-blue-50">
        <div className="max-w-7xl mx-auto font-nunito  max-sm:p-4 ">
          {courses?.map((course, index) => {
            const qty = course?.quantity || 1;
            const quantity = Number(qty) || 1;
            const courseFee = Number(selectedPlan?.course_fee) || 0;
            const discountPercent = Number(selectedPlan?.discount) || 0;
            const taxPercent = Number(selectedPlan?.tax) || 0;
            const totalOriginalPrice = courseFee * quantity;
            const totalDiscount =
              ((courseFee * discountPercent) / 100) * quantity;
            const subtotal = totalOriginalPrice - totalDiscount;
            const taxAmount = (subtotal * taxPercent) / 100;
            let PlangrandTotal = (subtotal + taxAmount).toFixed(2);
            if (promoData) {
              const promoDiscountPercent =
                Number(promoData?.discount_percentage) || 0;
              const subtotalNum = Number(subtotal);
              const taxAmountNum = Number(taxAmount);
              const promoDiscountAmount =
                ((subtotalNum + taxAmountNum) * promoDiscountPercent) / 100;
              PlangrandTotal = (
                subtotalNum +
                taxAmountNum -
                promoDiscountAmount
              ).toFixed(2);
            }

            // for show day/date count of total days
            const getClassDates = (startStr, endStr, batchType) => {
              if (!startStr || !endStr || !batchType) return [];
              const startDate = new Date(`${startStr}T00:00:00`);
              const endDate = new Date(`${endStr}T00:00:00`);
              if (isNaN(startDate) || isNaN(endDate)) return [];
              const batch = batchType.toLowerCase();
              const dates = [];
              for (
                let d = new Date(startDate);
                d <= endDate;
                d.setDate(d.getDate() + 1)
              ) {
                const day = d.getDay();

                if (batch === "weekday") {
                  dates.push(new Date(d));
                }
                if (batch === "weekend" && (day === 0 || day === 6)) {
                  dates.push(new Date(d));
                }
              }
              return dates;
            };
            const classDates = getClassDates(
              course?.class_start_date,
              course?.class_end_date,
              course?.batch_type,
            );
            const formatDayDate = (date) => {
              const day = date
                .toLocaleDateString("en-US", { weekday: "short" })
                .toUpperCase();
              const dayNum = date.getDate();
              return `${day}/${dayNum}`;
            };
            const {
              totalOriginal,
              // subtotal,
              // taxAmount,
              // taxPercent,
              totalPrice,
              totalSave,
              // discountPercent,
              isDiscountActive,
              offerFor,
            } = getTotals(course, qty, selectedPlan);

            return (
              <>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-2 items-start">
                  <div className="flex max-sm:flex-col gap-2 items-center max-sm:py-3">
                    <h1 className="text-2xl font-bold  md:py-5 ">
                      Order Summary For
                    </h1>
                    <span className="">
                      (
                      {course?.url_title
                        ?.replace(/-/g, " ")
                        ?.replace(/\s+/g, " ")
                        ?.trim()
                        ?.replace(/\b\w/g, (c) => c.toUpperCase())}
                      )
                    </span>
                  </div>

                  <div className="max-sm:pb-2">
                    {sessionActive && enrollmentData && (
                      <div className="bg-green-100 text-green-800 border-2 border-green-500 py-1 px-4 rounded-full mt-4 font-medium flex justify-between items-center text-sm">
                        <span>
                          Hurry! Your enrollment is secured for{" "}
                          {Math.floor(timeLeft / 60)
                            .toString()
                            .padStart(2, "0")}
                          :{(timeLeft % 60).toString().padStart(2, "0")}{" "}
                          minutes. Complete your payment to confirm it.
                        </span>
                      </div>
                    )}
                  </div>
                </div>

                <div className="pb-10">
                  <div className="grid grid-cols-1 md:grid-cols-[60%_40%] gap-4">
                    <div className=" rounded-xl  space-y-2">
                      {/* ====================== STEP 1 ====================== */}
                      <div className="border border-gray-200 rounded-lg bg-white  ">
                        <button
                          onClick={() => toggleStep(1)}
                          className="w-full p-4 flex justify-between items-center cursor-pointer border-b border-gray-200"
                        >
                          <div className="flex gap-2">
                            <h2 className="font-bold text-lg">
                              {" "}
                              1. Basic Details{" "}
                            </h2>

                            {enrollmentData && (
                              <span className="inline-flex items-center text-xs font-medium bg-blue-100 text-blue-800 px-2 rounded-full border border-blue-400">
                                {enrollmentData?.data?.first_name}{" "}
                                {enrollmentData?.data?.last_name}
                              </span>
                            )}
                          </div>
                          {openStep === 1 ? <FiChevronUp /> : <FiChevronDown />}
                        </button>
                        {openStep === 1 && (
                          <div className="space-y-4 animate-fadeIn p-4">
                            {sessionActive && enrollmentData ? (
                              <div className="bg-white  ">
                                <h2 className="text-lg font-semibold text-gray-800 mb-2">
                                  Enrollment Details
                                </h2>

                                <div className="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-4 text-sm">
                                  <div>
                                    <p className="text-gray-500">First Name</p>
                                    <p className="font-medium text-gray-800">
                                      {enrollmentData?.data?.first_name}
                                    </p>
                                  </div>

                                  <div>
                                    <p className="text-gray-500">Last Name</p>
                                    <p className="font-medium text-gray-800">
                                      {enrollmentData?.data?.last_name}
                                    </p>
                                  </div>

                                  <div>
                                    <p className="text-gray-500">Email</p>
                                    <p className="font-medium text-gray-800 break-all">
                                      {enrollmentData?.data?.email}
                                    </p>
                                  </div>

                                  <div>
                                    <p className="text-gray-500">Phone</p>
                                    <p className="font-medium text-gray-800">
                                      +{enrollmentData?.data?.country_code}{" "}
                                      {enrollmentData?.data?.phone}
                                    </p>
                                  </div>

                                  <div>
                                    <p className="text-gray-500">Enroll Type</p>
                                    <p className="font-medium text-gray-800 capitalize">
                                      {enrollmentData?.data?.enroll_type}
                                    </p>
                                  </div>

                                  <div>
                                    <p className="text-gray-500">Created At</p>
                                    <p className="font-medium text-gray-800">
                                      {new Date(
                                        enrollmentData?.data?.created_at,
                                      ).toLocaleString()}
                                    </p>
                                  </div>

                                  <div>
                                    <p className="text-gray-500">
                                      Alternate Contact
                                    </p>
                                    <p className="font-medium text-gray-800">
                                      {enrollmentData?.data
                                        ?.alternate_contact || "Not Provided"}
                                    </p>
                                  </div>

                                  <div>
                                    <p className="text-gray-500">
                                      Alternate Email
                                    </p>
                                    <p className="font-medium text-gray-800 break-all">
                                      {enrollmentData?.data?.alternate_email ||
                                        "Not Provided"}
                                    </p>
                                  </div>
                                </div>
                              </div>
                            ) : (
                              <div>
                                <form action="">
                                  <div className="flex max-sm:flex-col gap-2 items-center">
                                    <div className="text-black font-bold">
                                      Enrolling for
                                    </div>
                                    <div className="flex items-center flex-wrap gap-6">
                                      <label className="flex items-center gap-2">
                                        <input
                                          type="radio"
                                          name="enrollType"
                                          value="myself"
                                          checked={
                                            formData.enrollType === "myself"
                                          }
                                          onChange={handleChange}
                                        />
                                        <span>For Myself</span>
                                      </label>

                                      <label className="flex items-center gap-2">
                                        <input
                                          type="radio"
                                          name="enrollType"
                                          value="someone"
                                          checked={
                                            formData.enrollType === "someone"
                                          }
                                          onChange={handleChange}
                                        />
                                        <span>Someone Else</span>
                                      </label>

                                      <label className="flex items-center gap-2">
                                        <input
                                          type="radio"
                                          name="enrollType"
                                          value="corporate"
                                          checked={
                                            formData.enrollType === "corporate"
                                          }
                                          onChange={handleChange}
                                        />
                                        <span>Corporate</span>
                                      </label>
                                    </div>
                                  </div>
                                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                    <div>
                                      <input
                                        className="form-input"
                                        placeholder="First Name*"
                                        name="first_name"
                                        value={formData.first_name}
                                        onChange={handleChange}
                                      />
                                      {errors.first_name && (
                                        <p className="text-red-500 text-sm mt-2 font-semibold">
                                          {errors.first_name}
                                        </p>
                                      )}
                                    </div>
                                    <div>
                                      <input
                                        className="form-input"
                                        placeholder="Last Name*"
                                        name="last_name"
                                        value={formData.last_name}
                                        onChange={handleChange}
                                      />
                                      {errors.last_name && (
                                        <p className="text-red-500 text-sm mt-2 font-semibold">
                                          {errors.last_name}
                                        </p>
                                      )}
                                    </div>
                                    {formData.enrollType === "corporate" && (
                                      <div>
                                        <input
                                          className="form-input"
                                          placeholder="Company Name*"
                                          name="company_name"
                                          value={formData.company_name}
                                          onChange={handleChange}
                                        />

                                        {errors.company_name && (
                                          <p className="text-red-500 text-sm mt-2 font-semibold">
                                            {errors.company_name}
                                          </p>
                                        )}
                                      </div>
                                    )}
                                  </div>
                                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                                    <div className="grid grid-cols-[27%_70%] gap-3">
                                      <div>
                                        <select
                                          name="country_code"
                                          value={formData?.country_code}
                                          onChange={handleChange}
                                          className="form-input"
                                        >
                                          <option value="">-Code-</option>
                                          {countryLists?.map((item, index) => (
                                            <option
                                              key={index}
                                              value={item?.phonecode}
                                            >
                                              {item?.phonecode}
                                            </option>
                                          ))}
                                        </select>
                                      </div>

                                      <div>
                                        <input
                                          type="text"
                                          name="phone"
                                          value={formData.phone}
                                          onChange={(e) => {
                                            const val = e.target.value;
                                            if (
                                              /^[0-9]*$/.test(val) &&
                                              val.length <= 10
                                            ) {
                                              handleChange(e);
                                            }
                                          }}
                                          placeholder="Contact Number*"
                                          className="form-input"
                                        />

                                        {errors?.phone && (
                                          <p className="text-red-500 text-sm mt-2 font-semibold">
                                            {errors?.phone}
                                          </p>
                                        )}
                                      </div>
                                    </div>
                                    <div className="grid grid-cols-1  md:grid-cols-[70%_30%] gap-2 items-start">
                                      <div>
                                        <input
                                          className="form-input w-full"
                                          type="email"
                                          name="email"
                                          value={formData.email}
                                          onChange={handleChange}
                                          placeholder="Email*"
                                        />
                                        {errors.email && (
                                          <p className="text-red-500 text-sm mt-2 font-semibold">
                                            {errors.email}
                                          </p>
                                        )}
                                      </div>
                                      <div>
                                        {!showAlternate ? (
                                          <button
                                            type="button"
                                            title="Add Alternate Contact"
                                            onClick={() =>
                                              setShowAlternate(true)
                                            }
                                            className="form-input cursor-pointer text-blue-500"
                                          >
                                            + Alternate
                                          </button>
                                        ) : (
                                          <button
                                            type="button"
                                            title="Remove Alternate Contact"
                                            onClick={() =>
                                              setShowAlternate(false)
                                            }
                                            className="form-input cursor-pointer text-red-500"
                                          >
                                            - Remove
                                          </button>
                                        )}
                                      </div>
                                    </div>

                                    {showAlternate && (
                                      <>
                                        <div className="grid grid-cols-[27%_70%] gap-3">
                                          <select
                                            name="alternate_country_code"
                                            value={
                                              formData.alternate_country_code
                                            }
                                            onChange={handleChange}
                                            className="form-input"
                                          >
                                            {countryLists.map((item, index) => (
                                              <option
                                                key={index}
                                                value={item.phonecode}
                                              >
                                                {item.phonecode}
                                              </option>
                                            ))}
                                          </select>

                                          <input
                                            className="form-input"
                                            name="alternate_contact"
                                            value={formData.alternate_contact}
                                            onChange={(e) => {
                                              const val = e.target.value;
                                              if (
                                                /^[0-9]*$/.test(val) &&
                                                val.length <= 10
                                              ) {
                                                handleChange(e);
                                              }
                                            }}
                                            placeholder="Alternate Phone"
                                          />
                                        </div>
                                        <input
                                          className="form-input"
                                          type="email"
                                          name="alternate_email"
                                          value={formData.alternate_email}
                                          onChange={handleChange}
                                          placeholder="Alternate Email"
                                        />
                                      </>
                                    )}
                                  </div>

                                  <div className="flex justify-end mt-2">
                                    <button
                                      type="button"
                                      onClick={handleSubmit}
                                      className="bg-[#882CFB] hover:bg-[#4347ca] cursor-pointer text-white px-6 py-2 rounded-full text-sm"
                                    >
                                      Continue
                                    </button>
                                  </div>
                                </form>
                              </div>
                            )}
                          </div>
                        )}
                      </div>

                      {/* ====================== STEP 2 ====================== */}
                      <div className="border border-gray-200 rounded-lg bg-white  ">
                        <button
                          onClick={() => toggleStep(2)}
                          className="w-full p-4 flex justify-between items-center cursor-pointer border-b border-gray-200"
                        >
                          <h2 className="font-bold text-lg">2. Choose Plan</h2>
                          {openStep === 2 && enrollmentData ? (
                            <FiChevronUp />
                          ) : (
                            <FiChevronDown />
                          )}
                        </button>

                        {openStep === 2 && enrollmentData && (
                          <div className="p-4 space-y-4 animate-fadeIn">
                            {/* Plans */}
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                              {coursePlan?.map((plan, index) => {
                                const originalPrice = Number(plan.course_fee);
                                const discountPercent =
                                  Number(plan.discount) || 0;
                                const taxPercent = Number(plan.tax) || 0;
                                const quantity = Number(qty) || 1;
                                const totalOriginalPrice =
                                  originalPrice * quantity;
                                const totalDiscountAmount =
                                  ((originalPrice * discountPercent) / 100) *
                                  quantity;
                                const totalAfterDiscount =
                                  totalOriginalPrice - totalDiscountAmount;
                                const taxAmount =
                                  (totalAfterDiscount * taxPercent) / 100;
                                let grandTotal = totalAfterDiscount + taxAmount;
                                let promoDiscountAmount = 0;
                                if (
                                  promoData &&
                                  selectedPlan?.plan_id === plan.plan_id
                                ) {
                                  const promoPercent =
                                    Number(promoData?.discount_percentage) || 0;
                                  promoDiscountAmount =
                                    (grandTotal * promoPercent) / 100;
                                  grandTotal -= promoDiscountAmount;
                                }
                                // const included_features = plan?.included_features
                                //   ? JSON?.parse(plan?.included_features)
                                //   : [];
                                //   const excluded_features = plan?.excluded_features
                                // ? JSON?.parse(plan?.excluded_features)
                                // : [];
                                const isPlanSelected =
                                  selectedPlan?.plan_id === plan.plan_id;

                                return (
                                  <div
                                    key={plan.plan_id || index}
                                    className={`relative border rounded-lg p-4 cursor-pointer transition hover:shadow-md ${
                                      isPlanSelected
                                        ? "border-green-500 border-2 shadow"
                                        : "border-gray-300"
                                    }`}
                                    onClick={() => setSelectedPlan(plan)}
                                  >
                                    {plan?.recommended === "yes" && (
                                      <div className="bg-green-500 text-white text-xs px-3 py-1 rounded-br-lg rounded-tl-lg mb-2 absolute top-0 left-0 flex gap-2 items-center">
                                        <span className="text-white text-xs font-semibold">
                                          Recommended
                                        </span>
                                      </div>
                                    )}

                                    <h3 className="text-2xl font-bold mb-2 mt-4">
                                      {plan?.plan || "NA"}
                                    </h3>
                                    <hr className="border-1 border-gray-200 border-dashed" />
                                    <div className="min-h-[270px] max-h-[300px] overflow-y-auto">
                                      <ul className="space-y-2 mt-2">
                                        {plan?.included_features?.map(
                                          (feature, i) => (
                                            <li
                                              key={i}
                                              className="flex items-start gap-2 text-sm"
                                            >
                                              <span className="text-green-600 mt-0.5">
                                                <IoCheckmarkCircle className="text-md" />
                                              </span>
                                              <span className="para">
                                                {feature}
                                              </span>
                                            </li>
                                          ),
                                        )}
                                      </ul>
                                      <ul className="space-y-2 mt-4">
                                        {plan?.excluded_features?.map(
                                          (feature, i) => (
                                            <li
                                              key={i}
                                              className="flex items-start gap-2 text-sm"
                                            >
                                              <span className="text-red-600 mt-0.5">
                                                <IoCloseCircle className="text-md" />
                                              </span>
                                              <span className="para">
                                                {feature}
                                              </span>
                                            </li>
                                          ),
                                        )}
                                      </ul>
                                    </div>

                                    {/* Price Details */}
                                    {plan?.plan !== "Corporate Plan" &&
                                      plan?.plan !== "corporate plan" && (
                                        <div className="flex flex-col gap-1 my-4 text-sm">
                                          {discountPercent > 0 && (
                                            <div className="flex items-center justify-between">
                                              <span>Original Price</span>
                                              <span className="line-through text-red-500">
                                                {plan?.currency || ""}{" "}
                                                {Number(
                                                  totalOriginalPrice,
                                                ).toFixed(2)}{" "}
                                              </span>
                                            </div>
                                          )}

                                          {discountPercent > 0 && (
                                            <div className="flex items-center justify-between text-green-600">
                                              <span>
                                                Discount{" "}
                                                <span className="bg-green-400 text-xs text-white border border-gray-200 rounded-full px-1">
                                                  {discountPercent}%
                                                </span>
                                              </span>
                                              <span>
                                                - {plan?.currency || ""}{" "}
                                                {totalDiscountAmount.toFixed(2)}
                                              </span>
                                            </div>
                                          )}

                                          {taxPercent > 0 && (
                                            <div className="flex items-center justify-between text-blue-600">
                                              <span>
                                                Tax{" "}
                                                <span className="bg-blue-400 text-xs text-white border border-gray-200 rounded-full px-1">
                                                  {taxPercent}%
                                                </span>
                                              </span>
                                              <span>
                                                + {plan?.currency || ""}{" "}
                                                {taxAmount.toFixed(2)}
                                              </span>
                                            </div>
                                          )}

                                          {promoDiscountAmount > 0 && (
                                            <div className="flex items-center justify-between text-purple-600">
                                              <span>
                                                Promo{" "}
                                                <span className="bg-purple-400 text-xs text-white border border-gray-200 rounded-full px-1">
                                                  {Number(
                                                    promoData?.discount_percentage,
                                                  ).toFixed(2)}
                                                  %
                                                </span>
                                              </span>
                                              <span>
                                                - {plan?.currency || ""}{" "}
                                                {Number(
                                                  promoDiscountAmount,
                                                ).toFixed(2)}
                                              </span>
                                            </div>
                                          )}
                                          <hr className="border-1 border-gray-200 border-dashed my-2" />

                                          <div className="flex items-center justify-between font-bold text-[#2E318D] text-lg">
                                            <span>Grand Total</span>
                                            <span>
                                              {plan?.currency || ""}{" "}
                                              {Number(grandTotal).toFixed(2)}
                                            </span>
                                          </div>
                                        </div>
                                      )}
                                    {/* <button
                                      onClick={() => setSelectedPlan(plan)}
                                      className={`w-full py-2 rounded-full text-white ${
                                        isPlanSelected
                                          ? "bg-[#2E318D]"
                                          : "bg-gray-700 hover:bg-gray-800"
                                      }`}
                                    >
                                      {plan?.plan}
                                    </button> */}
                                    {/* <div className="flex items-center justify-center">
                                      {selectedPlan && (
                                        <button
                                          onClick={() => setOpenStep(3)}
                                          className={`cursor-pointer text-white px-6 py-2 rounded-full text-sm transition-all
                                              ${
                                                isPlanSelected
                                                  ? "bg-green-600 hover:bg-green-700"
                                                  : "bg-[#882CFB] hover:bg-[#4347ca] border border-gray-300"
                                              }
                                            `}
                                        >
                                          Continue For Payment{" "}
                                        </button>
                                      )}
                                    </div> */}
                                    <div className="flex items-center justify-center">
                                      {isPlanSelected &&
                                        plan?.plan !== "Corporate Plan" &&
                                        plan?.plan !== "corporate plan" && (
                                          <button
                                            onClick={(e) => {
                                              e.stopPropagation();
                                              setOpenStep(3);
                                            }}
                                            className={`cursor-pointer text-white px-6 py-2 rounded-full text-sm transition-all
                                              ${
                                                isPlanSelected
                                                  ? "bg-green-600 hover:bg-green-700"
                                                  : "bg-[#882CFB] hover:bg-[#4347ca] border border-gray-300"
                                              }
                                              `}
                                          >
                                            Continue For Payment
                                          </button>
                                        )}
                                      {isPlanSelected &&
                                        (plan?.plan === "Corporate Plan" ||
                                          plan?.plan === "corporate plan") && (
                                          <div className="mt-8 flex gap-4">
                                            <FormModal
                                              buttonText="Contact Advisor"
                                              modalType="register"
                                            />
                                          </div>
                                        )}
                                    </div>
                                  </div>
                                );
                              })}
                            </div>
                            {/* <div className="flex justify-end">
                              {selectedPlan && (
                                <button
                                  onClick={() => setOpenStep(3)}
                                  className="bg-[#882CFB] hover:bg-[#4347ca] cursor-pointer text-white px-6 py-2 rounded-full text-sm"
                                >
                                  Continue with{" "}
                                  {`${selectedPlan?.currency || ""} ${Number(
                                    PlangrandTotal
                                  ).toFixed(2)}`}
                                </button>
                              )}
                            </div> */}
                          </div>
                        )}
                      </div>

                      {/* ====================== STEP 3 ====================== */}
                      <div className="border border-gray-200 rounded-lg bg-white  ">
                        <button
                          // onClick={() => toggleStep(3)}
                          className="w-full p-4 flex justify-between items-center cursor-pointer border-b border-gray-200"
                        >
                          <h2 className="font-bold text-lg">
                            3. Payment Method
                          </h2>
                          {openStep === 3 && enrollmentData && selectedPlan ? (
                            <FiChevronUp />
                          ) : (
                            <FiChevronDown />
                          )}
                        </button>

                        {openStep === 3 && enrollmentData && selectedPlan && (
                          <>
                            {/* Container */}
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 p-5">
                              {/* Razorpay */}
                              <div
                                onClick={() => setPaymentMethod("razorpay")}
                                className={`${methodBox} flex items-center gap-3 p-3 text-sm
                                hover:shadow-md hover:-translate-y-0.5
                                ${
                                  paymentMethod === "razorpay"
                                    ? "border-blue-600 bg-blue-50 shadow-blue-200"
                                    : "border-gray-300"
                                }
                              `}
                              >
                                <div
                                  className={`p-2 rounded-lg ${
                                    paymentMethod === "razorpay"
                                      ? "bg-blue-600 text-white"
                                      : "bg-blue-100 text-blue-600"
                                  }`}
                                >
                                  <SiRazorpay className="text-xl" />
                                </div>
                                <div>
                                  <p className="font-semibold text-sm">
                                    Razorpay
                                  </p>
                                  <p className="text-gray-500 text-xs">
                                    Fast & secure payment
                                  </p>
                                </div>
                              </div>

                              {/* CCAvenue */}
                              <div
                                onClick={() => setPaymentMethod("ccavenue")}
                                className={`${methodBox} flex items-center gap-3 p-3 text-sm
                                  hover:shadow-md hover:-translate-y-0.5
                                  ${
                                    paymentMethod === "ccavenue"
                                      ? "border-green-600 bg-green-50 shadow-green-200"
                                      : "border-gray-300"
                                  }
                                `}
                              >
                                <div
                                  className={`p-2 rounded-lg ${
                                    paymentMethod === "ccavenue"
                                      ? "bg-green-600 text-white"
                                      : "bg-green-100 text-green-600"
                                  }`}
                                >
                                  <FaUniversity className="text-xl" />
                                </div>
                                <div>
                                  <p className="font-semibold text-sm">
                                    CCAvenue
                                  </p>
                                  <p className="text-gray-500 text-xs">
                                    Secure Indian gateway
                                  </p>
                                </div>
                              </div>
                            </div>
                            {/* Pay Button */}
                            {paymentMethod && (
                              <div className="text-right px-3 pb-3">
                                <button
                                  onClick={startPayment}
                                  disabled={loading || !paymentMethod}
                                  className="px-6 py-2 text-sm bg-green-600 hover:bg-green-700 text-white rounded-full shadow-md  transition-all cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                  {loading
                                    ? "Processing..."
                                    : `Pay Now ${
                                        selectedPlan?.currency || ""
                                      } ${Number(PlangrandTotal).toFixed(2)}`}
                                </button>
                              </div>
                            )}
                            <div className="p-5 text-sm border-t border-gray-200">
                              <p className="font-semibold mb-1">Disclaimer </p>
                              <p className="para">
                                Transactions on this site are safe & secure as
                                indicated by the secure lock in your address
                                bar. Over 100,000+ users like you have enrolled
                                for courses. If you face any challenges during
                                the payment transaction, take a screenshot and
                                share it with{" "}
                                <a
                                  href="mailto:support@scholaracad.com"
                                  className="text-blue-600 underline"
                                >
                                  support@scholaracad.com
                                </a>{" "}
                                and{" "}
                                <a
                                  href="mailto:info@scholaracad.com"
                                  className="text-blue-600 underline"
                                >
                                  info@scholaracad.com
                                </a>
                                . Our team will connect with you.
                              </p>
                              <div className="flex items-center gap-2">
                                {/* <span className="w-3 h-3 rounded-full bg-[#882CFB]"></span> */}
                                <p className="font-semibold my-2">Contact </p>
                              </div>
                              <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                {/* India */}
                                <div className="">
                                  <h4 className="font-semibold flex items-center gap-2 mb-2">
                                    <img
                                      src="/assets/landingpage/Flag_of_India.png"
                                      className="w-6 h-4 object-cover"
                                      alt="India Flag"
                                    />
                                    India
                                  </h4>

                                  <ul className="text-sm text-gray-700">
                                    <li>
                                      <a
                                        href="tel:+917441428302"
                                        className="hover:underline"
                                        title="Call India Support"
                                      >
                                        +91 74414 28302
                                      </a>
                                    </li>
                                  </ul>
                                </div>

                                {/* United Kingdom */}
                                <div className="">
                                  <h4 className="font-semibold flex items-center gap-2 mb-2">
                                    <img
                                      src="/assets/landingpage/Flag_of_US.png"
                                      className="w-6 h-4 object-cover"
                                      alt="UK Flag"
                                    />
                                    United Kingdom
                                  </h4>

                                  <ul className="text-sm text-gray-700">
                                    <li>
                                      <a
                                        href="tel:+447441428302"
                                        className="hover:underline"
                                        title="Call UK Support"
                                      >
                                        +44 7441 428302
                                      </a>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </>
                        )}
                      </div>
                    </div>

                    <div className="flex flex-col  gap-2">
                      <div
                        className={`bg-white border border-gray-200 rounded-lg p-3 transition
                          ${
                            !sessionActive
                              ? "opacity-60 cursor-not-allowed"
                              : "hover:shadow-sm"
                          }
                        `}
                      >
                        <h2 className="font-bold text-lg">
                          Have a Promo Code?
                        </h2>

                        <div className="mt-2 flex items-center gap-2">
                          <input
                            type="text"
                            placeholder={
                              sessionActive
                                ? "Enter Promo Code"
                                : "Session not active"
                            }
                            value={promoCode}
                            onChange={(e) => setPromoCode(e.target.value)}
                            disabled={!sessionActive || promoData}
                            className="flex-1 form-input disabled:bg-gray-100 disabled:cursor-not-allowed"
                          />

                          <button
                            type="button"
                            title="Apply"
                            onClick={verifyPromoCode}
                            disabled={!sessionActive || promoData}
                            className="bg-[#882CFB] hover:bg-[#4347ca]
                          disabled:opacity-50 disabled:cursor-not-allowed
                          text-white px-6 py-2 rounded-full text-sm cursor-pointer"
                          >
                            {promoData ? "Applied" : "Apply"}
                          </button>
                        </div>

                        {promoData && (
                          <p className="mt-2 text-green-600 text-sm">
                            Promo "{promoData.promocode}" applied! You saved{" "}
                            {promoData.discount_percentage}%.
                          </p>
                        )}

                        {!sessionActive && (
                          <p className="mt-2 text-sm text-red-500">
                            Promo code can be applied only when the Basic
                            details is filled.
                          </p>
                        )}
                      </div>

                      <div
                        key={course?.schedule_id || index}
                        className="bg-white  border border-gray-200 relative  rounded-lg   "
                      >
                        {/* <div className="bg-gradient-to-r from-[#2E318D] to-[#882CFB] text-white text-xs px-3 py-1 rounded-br-lg rounded-tl-lg mb-2 absolute top-0 left-0 flex gap-2 items-center ">
                          <span className="flex gap-1 item-center">
                            <span> SPECIAL OFFER : </span>
                            <span className="flex gap-1 item-center">
                              <span>Save Flat {course?.discount_amt}</span>
                              <span>
                                On {course?.course_fees} <span> </span>
                                {course?.fee_currency}
                              </span>
                            </span>
                          </span>
                          <div className="relative group inline-block">
                            <span className="cursor-pointer  ">
                              <FiAlertCircle />
                            </span>
                            <div
                              className="
                                   absolute left-6 top-1 border border-gray-200
                                   invisible opacity-0 group-hover:visible group-hover:opacity-100
                                   transition-all duration-200
                                   bg-white text-gray-700 text-sm
                                   p-2 rounded shadow-md w-60 z-20
                                 "
                            >
                              <p className=" text-xs">
                                Discount offer , Term & Conditions Special
                                offers not applicable for ...... Participents
                              </p>
                            </div>
                          </div>
                        </div> */}

                        <div className=" px-2">
                          <div className="p-2">
                            <div className="flex justify-between md:flex-row flex-col items-center border-b-2 py-2 border-dashed border-gray-200">
                              <h2 className="font-bold text-lg">
                                Course Schdule Information
                              </h2>
                              {course?.batch_type && (
                                <span className="px-3 py-1 rounded-full bg-green-200 border text-sm border-green-500 text-green-700">
                                  {capitalizeFirst(course?.batch_type)}
                                </span>
                              )}
                            </div>
                            {/* <div className="flex md:justify-end">
                              <button
                                onClick={() =>
                                  handleRemoveCourse(course?.schedule_id)
                                }
                                title="Remove"
                                className="relative overflow-hidden px-3 py-1 cursor-pointer bg-red-500 hover:bg-red-700 text-white rounded-full text-sm  flex gap-1 item-center"
                              >
                                Remove <BsFillCartDashFill className="mt-1" />
                              </button>
                            </div> */}

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 text-[16px]">
                              <div className="flex items-center gap-3">
                                <div className="bg-[#F9FAFB] border border-gray-100 p-3 rounded-md flex items-center justify-center">
                                  <SlCalender className="text-green-600 text-md" />
                                </div>
                                <div className="flex flex-col">
                                  <span className="font-bold text-[#2E318D]">
                                    Start Date
                                  </span>
                                  <span className="font-normal text-[#333333]">
                                    {course?.class_start_date}
                                  </span>
                                </div>
                              </div>
                              <div className="flex items-center gap-3">
                                <div className="bg-[#F9FAFB] border border-gray-100 p-3 rounded-md flex items-center justify-center">
                                  <HiOutlineCalendarDateRange className="text-red-600 text-md" />
                                </div>
                                <div className="flex flex-col">
                                  <span className="font-bold text-[#2E318D]">
                                    End Date
                                  </span>
                                  <span className="font-normal text-[#333333]">
                                    {course?.class_end_date}
                                  </span>
                                </div>
                              </div>
                            </div>
                            {/* <div class="flex gap-3 mt-3">
                              <div class="w-10 border border-blue-300 rounded-sm shadow-sm bg-white">
                                <div class="h-2 bg-[#F7B439] rounded-t-sm"></div>
                                <div class="flex flex-col items-center ">
                                  <p class="text-sm font-bold">06</p>
                                  <p class="text-gray-600 text-sm">Sat</p>
                                </div>
                              </div>

                              <div class="w-10 border border-blue-300 rounded-sm shadow-sm bg-white">
                                <div class="h-2 bg-[#F7B439] rounded-t-sm"></div>
                                <div class="flex flex-col items-center ">
                                  <p class="text-sm font-bold">07</p>
                                  <p class="text-gray-600 text-sm">Sun</p>
                                </div>
                              </div>

                              <div class="w-10 border border-blue-300 rounded-sm shadow-sm bg-white">
                                <div class="h-2 bg-[#F7B439] rounded-t-sm"></div>
                                <div class="flex flex-col items-center ">
                                  <p class="text-sm font-bold">13</p>
                                  <p class="text-gray-600 text-sm">Sat</p>
                                </div>
                              </div>

                              <div class="w-10 border border-blue-300 rounded-sm shadow-sm bg-white">
                                <div class="h-2 bg-[#F7B439] rounded-t-sm"></div>
                                <div class="flex flex-col items-center ">
                                  <p class="text-sm font-bold">14</p>
                                  <p class="text-gray-600 text-sm">Sun</p>
                                </div>
                              </div>
                            </div> */}
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 text-[16px]">
                              <div className="flex items-center gap-3">
                                <div className="bg-[#F9FAFB] border border-gray-100 p-3 rounded-md flex items-center justify-center">
                                  <IoTimeOutline className="text-[#2E318D] text-lg" />
                                </div>
                                <div className="flex flex-col">
                                  <span className="font-bold text-[#2E318D]">
                                    Start Time
                                  </span>
                                  <span className="font-normal text-[#333333]">
                                    {course?.class_start_time}
                                  </span>
                                </div>
                              </div>
                              <div className="flex items-center gap-3">
                                <div className="bg-[#F9FAFB] border border-gray-100 p-3 rounded-md flex items-center justify-center">
                                  <IoIosTimer className="ext-[#2E318D] text-lg" />
                                </div>
                                <div className="flex flex-col">
                                  <span className="font-bold text-[#2E318D]">
                                    End Time
                                  </span>
                                  <span className="font-normal text-[#333333]">
                                    {course?.class_end_time}
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div className="space-y-2 px-3 my-4 ">
                            <p className="font-semibold text-lg">
                              Live Online Classroom
                            </p>
                            {/* <div className="flex items-center flex-wrap gap-2 text-gray-500">
                              <SlCalender className=" text-md" />
                              <span className="text-md">
                                Program Duration :
                              </span>
                              <span className="">
                                {course?.totalClassDays === 1
                                  ? "1 Day "
                                  : `${course?.totalClassDays} Days `}
                              </span>
                            </div> */}
                            <div className="flex gap-3 items-center flex-wrap">
                              {classDates?.map((date, index) => {
                                const day = date
                                  .toLocaleDateString("en-US", {
                                    weekday: "short",
                                  })
                                  .slice(0, 2)
                                  .toUpperCase();
                                const dayNum = date.getDate();
                                return (
                                  <div
                                    key={index}
                                    className="flex flex-col items-center bg-blue-50 py-1 border border-[#882CFB]  rounded-md p-3"
                                  >
                                    <span className="text-xs font-semibold text-gray-600 border-b">
                                      {day}
                                    </span>
                                    <span className="text-sm font-bold  text-gray-600">
                                      {dayNum}
                                    </span>
                                  </div>
                                );
                              })}
                            </div>
                            {/* <div className="flex items-center flex-wrap gap-2 text-gray-500">
                              <FiHash className=" text-md" />
                              <span className="text-md">Schedule ID :</span>
                              <span className="">
                                {course?.course_schedule_id}
                              </span>
                            </div> */}
                            <div className="flex items-center gap-2 text-gray-600">
                              <FiGlobe className=" text-md" />
                              <span className="">Language :</span>
                              <span className="">{course?.class_language}</span>
                            </div>
                            <div className="flex gap-2 flex-wrap  item-center space-y-1 mt-2 text-gray-600">
                              <div className="flex items-center gap-2">
                                <FiCheckCircle className=" text-md" />
                                <span className=" font-medium">Includes :</span>
                              </div>
                              <div className=" ">
                                {JSON.parse(
                                  course?.course_includes || "[]",
                                )?.map((item, idx) => (
                                  <div
                                    key={idx}
                                    className="flex items-center gap-2 "
                                  >
                                    <span>
                                      {item || "No Resources Included"}
                                    </span>
                                  </div>
                                ))}
                              </div>
                            </div>
                            <div className="flex items-center gap-2 text-gray-600">
                              <MdOutlineLocalOffer className="text-md" />
                              <span className="">Offer for :</span>
                              <span className="font-medium ">
                                {offerFor && (
                                  <p className="">{offerFor || "No Offer"}</p>
                                )}
                              </span>
                            </div>
                          </div>

                          <div className=" pb-2 flex flex-col justify-between border-t-2  border-dashed py-2 border-gray-300  px-2 mb-2">
                            <p className="font-semibold text-lg">Learners</p>
                            <div className="flex justify-between md:flex-row flex-col">
                              <div>
                                <div className="inline-flex border rounded-lg overflow-hidden mt-2">
                                  <button
                                    title="Decreament"
                                    className="px-4 py-2 hover:bg-gray-300 border-r cursor-pointer"
                                    onClick={() => decrement(index)}
                                  >
                                    <GrFormSubtract />
                                  </button>

                                  <span className="px-6 py-2">{qty}</span>

                                  <button
                                    title="Increament"
                                    className="px-4 py-2 hover:bg-gray-300 border-l cursor-pointer"
                                    onClick={() => increment(index)}
                                  >
                                    <IoIosAdd />
                                  </button>
                                </div>
                              </div>
                              <div className="mt-2">
                                <p className="text-2xl font-bold text-[#2E318D] flex gap-2">
                                  {Number(totalPrice).toFixed(2)}{" "}
                                  {course?.fee_currency}
                                  {isDiscountActive && totalSave > 0 && (
                                    <span className="line-through text-red-400 text-sm mt-1">
                                      {Number(totalOriginal).toFixed(2)}{" "}
                                      {course?.fee_currency}
                                    </span>
                                  )}
                                </p>
                                <div className="text-sm text-gray-600 mt-1 space-y-1">
                                  {isDiscountActive && totalSave > 0 && (
                                    <p>
                                      Subtotal :{" "}
                                      <span className="line-through">
                                        {Number(subtotal).toFixed(2)}{" "}
                                        {course?.fee_currency}
                                      </span>
                                    </p>
                                  )}

                                  {isDiscountActive && totalSave > 0 && (
                                    <p className="text-green-600 mt-1">
                                      Save ({discountPercent}%) :{" "}
                                      {Number(totalSave).toFixed(2)}{" "}
                                      {course?.fee_currency}
                                    </p>
                                  )}
                                  {taxAmount > 0 && (
                                    <p>
                                      Tax ({taxPercent}%) :{" "}
                                      {Number(taxAmount).toFixed(2)}{" "}
                                      {course?.fee_currency}
                                    </p>
                                  )}
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        {course?.enroll_end_date && (
                          <div className="flex max-sm:flex-col md:flex-row justify-between p-2 bg-gray-50 rounded-xl gap-4 text-xs font-medium">
                            {/* <div className="flex item-center gap-4  text-gray-700">
                            <div className="flex items-center gap-1 cursor-pointer hover:text-[#2E318D] transition">
                              <FiMail className="text-xs" />
                              <span>Email Schedule</span>
                            </div>
                            <div className="flex items-center gap-1 cursor-pointer hover:text-[#2E318D] transition">
                              <FiPrinter className="text-xs" />
                              <span>Print Schedule</span>
                            </div>
                            <div
                              onClick={() => scrollToSection("Contact")}
                              className="flex items-center gap-1 cursor-pointer hover:text-[#2E318D] transition"
                            >
                              <FiMessageCircle className="text-xs" />
                              <span>Enquire</span>
                            </div>
                          </div> */}
                            <div className="flex gap-2 items-center">
                              <span className="text-sm">
                                Registration Ends In
                              </span>
                              <span class="text-white bg-red-400 px-3 py-1 font-bold rounded-tr-xl rounded-bl-xl">
                                {course?.enroll_end_date}
                              </span>
                            </div>
                          </div>
                        )}
                      </div>
                    </div>
                  </div>
                </div>
              </>
            );
          })}

          {/* <div className="mt-10"> */}
          {/* Title */}
          {/* <h3 className="font-bold text-xl mb-4 text-gray-900">
              Choose Payment Method
            </h3> */}

          {/* Container */}
          {/* Razorpay */}
          {/* <div className="grid grid-cols-1 md:grid-cols-2 md:max-w-[50%]  gap-4 bgw">
              <div
                onClick={() => setPaymentMethod("razorpay")}
                className={`${methodBox} flex items-center gap-3 p-3 text-sm
                hover:shadow-md hover:-translate-y-0.5
                ${
                  paymentMethod === "razorpay"
                    ? "border-blue-600 bg-blue-50 shadow-blue-200"
                    : "border-gray-300"
                }
              `}
              >
                <div
                  className={`p-2 rounded-lg ${
                    paymentMethod === "razorpay"
                      ? "bg-blue-600 text-white"
                      : "bg-blue-100 text-blue-600"
                  }`}
                >
                  <SiRazorpay className="text-xl" />
                </div>
                <div>
                  <p className="font-semibold text-sm">Razorpay</p>
                  <p className="text-gray-500 text-xs">Fast & secure payment</p>
                </div>
              </div>

            
              <div
                onClick={() => setPaymentMethod("ccavenue")}
                className={`${methodBox} flex items-center gap-3 p-3 text-sm
                hover:shadow-md hover:-translate-y-0.5
                ${
                  paymentMethod === "ccavenue"
                    ? "border-green-600 bg-green-50 shadow-green-200"
                    : "border-gray-300"
                }
              `}
              >
                <div
                  className={`p-2 rounded-lg ${
                    paymentMethod === "ccavenue"
                      ? "bg-green-600 text-white"
                      : "bg-green-100 text-green-600"
                  }`}
                >
                  <FaUniversity className="text-xl" />
                </div>
                <div>
                  <p className="font-semibold text-sm">CCAvenue</p>
                  <p className="text-gray-500 text-xs">Secure Indian gateway</p>
                </div>
              </div>
            </div> */}
          {/* Pay Button */}
          {/* {paymentMethod && (
              <div className="text-right mt-8">
                <button
                  onClick={startPayment}
                  disabled={loading || !paymentMethod}
                  className="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-full shadow-md text-lg transition-all cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  {loading
                    ? "Processing..."
                    : `Pay Now ₹${grandTotal.toLocaleString()}`}
                </button>
              </div>
            )} */}
          {/* </div> */}
        </div>
      </section>
      {/* <hr className="text-gray-200" /> */}
      {/* <Footer /> */}

      <footer className="w-full border-t border-gray-200 bg-white ">
        <div className="max-w-7xl mx-auto  py-4 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
          <div className="flex flex-col md:flex-row items-center  gap-2 text-gray-600">
            <div className="flex gap-1 items-center">
              <FiLock className="text-green-600" />
              <span>100% Secure Payments</span>
            </div>
            <div className="flex gap-1 items-center">
              <span className="hidden md:inline">•</span>
              <span>
                Copyright © {new Date().getFullYear()} All rights reserved by
                Scholaracad
              </span>
            </div>
          </div>
          <div className="flex flex-col md:flex-row items-center flex-wrap justify-end gap-4 text-gray-600">
            <div className="flex gap-1 items-center">
              <Link
                href="/term-and-condition"
                className="flex items-center gap-1 hover:text-blue-600 transition"
              >
                <FiHelpCircle />
                Terms & Conditions
              </Link>
            </div>
            <div className="flex gap-1 items-center">
              <Link
                href="/cancellationrefund-policy"
                className="flex items-center gap-1 hover:text-blue-600 transition"
              >
                <FiRefreshCcw />
                Cancellation & Refund Policy
              </Link>
            </div>
          </div>
        </div>
      </footer>
    </>
  );
}
