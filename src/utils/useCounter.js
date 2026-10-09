import { useState, useEffect } from "react";

export const useCounter = (startCount, startTrigger, duration = 1500) => {
  const [count, setCount] = useState(0);
  useEffect(() => {
    if (!startTrigger) return;
    let start = 0;
    const increment = startCount / (duration / 30);
    const timer = setInterval(() => {
      start += increment;
      if (start >= startCount) {
        setCount(startCount);
        clearInterval(timer);
      } else {
        setCount(Math.floor(start));
      }
    }, 30);

    return () => clearInterval(timer);
  }, [startTrigger, startCount, duration]);

  return count;
};
