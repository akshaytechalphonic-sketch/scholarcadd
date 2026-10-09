import { useAuth } from "@/context/AuthContext";
import { useEffect, useState } from "react";
import { FiCopy, FiCheck, FiX } from "react-icons/fi";

export default function PromoBanner() {
  const { promoList } = useAuth();
  const [visible, setVisible] = useState(true);
  const [copied, setCopied] = useState(false);
  const [copiedCode, setCopiedCode] = useState(null);
  const [remainingTime, setRemainingTime] = useState(null);
  const promo = promoList?.[0];

  const handleCopy = (code) => {
    if (!code) return;
    navigator.clipboard.writeText(code);
    setCopied(true);
    setCopiedCode(code);
    setTimeout(() => {
      setCopied(false);
      setCopiedCode(null);
    }, 3000);
  };

  const getRemainingTime = (endDate) => {
    if (!endDate) return null;
    const now = new Date().getTime();
    const end = new Date(endDate).getTime();
    const diff = end - now;
    if (diff <= 0) return null;

    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
    const minutes = Math.floor((diff / (1000 * 60)) % 60);
    const seconds = Math.floor((diff / 1000) % 60);

    return { days, hours, minutes, seconds };
  };

  useEffect(() => {
    if (!promo) return;

    setRemainingTime(getRemainingTime(promo.end_date));

    const interval = setInterval(() => {
      setRemainingTime(getRemainingTime(promo.end_date));
    }, 1000);

    return () => clearInterval(interval);
  }, [promo]);

  if (!visible || !promo || !remainingTime) return null;

  return (
    <div className="w-full relative bg-gradient-to-r from-black via-purple-600 to-pink-500 text-white">
      <div className="max-w-7xl mx-auto py-1">
        <div className="p-1 flex  md:items-center md:justify-between gap-4">
          <div className="animate-lightSlide">
            <h3 className="text-lg font-semibold">🎉 {promo.text}</h3>
            <p className="text-sm opacity-90">
              Get{" "}
              <span className="font-bold bg-red-500 rounded-lg px-2">
                {promo.discount_percentage}% OFF
              </span>{" "}
              {promo?.course_type && <span>On {promo.course_type}</span>}
              <span></span>
            </p>
          </div>
          <div className="flex gap-3 text-center max-sm:hidden">
            {["days", "hours", "minutes", "seconds"].map((unit) => (
              <div
                key={unit}
                className="bg-white/20 backdrop-blur rounded-md px-3"
              >
                <div className="text-md font-bold">{remainingTime?.[unit]}</div>
                <div className="text-xs uppercase opacity-80">{unit}</div>
              </div>
            ))}
          </div>
          <div className="flex items-center gap-3">
            <div className="bg-white/20 backdrop-blur px-4 py-2 rounded-lg flex items-center gap-2">
              <span className="text-sm">Code :</span>
              <span className="font-mono font-bold">{promo.promocode}</span>
              <button
                title={copied ? "Copied!" : "Copy"}
                onClick={() => handleCopy(promo.promocode)}
                className="hover:opacity-80 transition cursor-pointer"
              >
                {copied ? <FiCheck /> : <FiCopy />}
              </button>
            </div>

            <button
              onClick={() => setVisible(false)}
              className="text-white hover:opacity-70 transition"
            >
              <FiX />
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
