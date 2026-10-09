import React from "react";
import { RiEdit2Line } from "react-icons/ri";
import { RiRefreshLine } from "react-icons/ri";
import Modal from "../Components/modal";
import React, { useState } from "react";

function login() {
  const [openModal, setOpenModal] = useState(null);

  return (
    <>
      {/* Open Modal Button for Login */}
      <button
        onClick={() => setOpenModal("login")}
        className="px-5 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700"
      >
        Open Modal Login
      </button>

      <Modal
        isOpen={openModal === "login"}
        onClose={() => setOpenModal(null)}
        title="Request For Login"
        width="max-w-lg"
        position="center"
      >
        <form className="space-y-5 mb-12">
          {/* Header */}
          <div>
            <h2 className="text-xl font-bold text-gray-800">Welcome!</h2>
            <p className="text-gray-500 text-sm">
              Sign up or Login to your account
            </p>
          </div>

          <div className="">
            <input
              type="text"
              placeholder="Enter your phone number or email"
              className="form-input"
              required
            />
          </div>

          <div className="flex justify-between items-center">
            <div className="">
              <div className="flex items-start gap-2">
                <label htmlFor="whatsapp" className="text-sm text-gray-600">
                  By Signing up, you agree to our
                </label>
              </div>
              <div className="flex gap-2">
                <a
                  href="/"
                  className="font-semibold text-indigo-400 text-xs underline"
                >
                  Terms and Conditions
                </a>
                <p className="text-sm text-gray-500">and</p>
                <a
                  href="/"
                  className="font-semibold text-indigo-400 text-xs underline"
                >
                  Privacy Policy
                </a>
              </div>
            </div>
            {/* Submit Button */}
            <div className="">
              <button
                type="submit"
                title="Submit"
                className="px-6 py-2  bg-[#2E318D] cursor-pointer text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition"
              >
                Submit
              </button>
            </div>
          </div>
        </form>
      </Modal>

      {/* Open Modal Button for Validate OTP */}
      <button
        onClick={() => setOpenModal("verifyotp")}
        className="px-5 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700"
      >
        Open Modal Validate
      </button>

      <Modal
        isOpen={openModal === "verifyotp"}
        onClose={() => setOpenModal(null)}
        title="Request For Verify OTP"
        width="max-w-lg"
        position="center"
      >
        <form className="space-y-5 mb-8">
          {/* Header */}
          <div>
            <h2 className="text-xl font-bold text-gray-800">Verify OTP</h2>
            <p className="text-gray-500 text-sm flex gap-2 items-center">
              We’ve sent an OTP to test@gmail.com
              <a
                href=""
                className="px-2 text-green-600 hover:underline font-semibold flex gap-1 items-center"
              >
                <RiEdit2Line />
                Edit
              </a>
            </p>
          </div>

          <div className="">
            <input
              type="text"
              placeholder="Enter OTP"
              className="form-input"
              required
            />
          </div>
          <div className="flex justify-between items-center">
            <div className="flex items-start gap-2">
              <p className="text-sm text-gray-600 flex gap-1 items-center">
                Didn’t receive OTP? Time Left: 66s
                <a
                  href=""
                  className="px-2 text-green-600 hover:underline font-semibold flex gap-1 items-center"
                >
                  <RiRefreshLine />
                  Resend OTP
                </a>
              </p>
            </div>
            {/* Submit Button */}
            <div className="">
              <button
                type="submit"
                title="Submit"
                className="px-6 py-2 w-full bg-[#2E318D] cursor-pointer text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition"
              >
                Continue
              </button>
            </div>
          </div>
        </form>
      </Modal>
    </>
  );
}

export default login;
