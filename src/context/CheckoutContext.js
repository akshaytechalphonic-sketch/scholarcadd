import { createContext, useContext, useState, useEffect } from "react";
const CheckoutContext = createContext();
export const CheckoutProvider = ({ children }) => {
  const [checkoutData, setCheckoutDataState] = useState(() => {
    if (typeof window !== "undefined") {
      const saved = sessionStorage.getItem("checkoutData");
      return saved ? JSON.parse(saved) : [];
    }
    return [];
  });
  // Persist to sessionStorage whenever checkoutData changes
  useEffect(() => {
    sessionStorage.setItem("checkoutData", JSON.stringify(checkoutData));
  }, [checkoutData]);
  // Add or update a single course
  const setCheckoutData = (course) => {
    setCheckoutDataState((prev = []) => {
      const index = prev.findIndex((c) => c?.schedule_id === course?.schedule_id);
      if (index > -1) {
        const updated = [...prev];
        updated[index] = { ...course };
        return updated;
      } else {
        return [...prev, course];
      }
    });
  };
  // Update entire array of courses (for increment/decrement)
  const updateMultipleCourses = (coursesArray) => {
    setCheckoutDataState(coursesArray);
  };

  const removeCourse = (schedule_id) => {
    setCheckoutDataState((prev = []) =>
      prev.filter((c) => c.schedule_id !== schedule_id)
    );
  };
  const clearCheckout = () => setCheckoutDataState([]);
  return (
    <CheckoutContext.Provider
      value={{
        checkoutData,
        setCheckoutData,
        updateMultipleCourses, 
        removeCourse,
        clearCheckout,
      }}
    >
      {children}
    </CheckoutContext.Provider>
  );
};

export const useCheckout = () => useContext(CheckoutContext);
