import CryptoJS from "crypto-js";
const SECRET_KEY = "scholaracadwebsitedevelopmentkey";

//  Encrypt ID
export function encryptId(id) {
  try {
    return CryptoJS.AES.encrypt(String(id), SECRET_KEY).toString();
  } catch (error) {
    console.error("Encryption failed:", error);
    return null;
  }
}
//  Decrypt ID
export function decryptId(encryptedId) {
  try {
    const bytes = CryptoJS.AES.decrypt(encryptedId, SECRET_KEY);
    const decrypted = bytes.toString(CryptoJS.enc.Utf8);
    return decrypted || null;
  } catch (error) {
    console.error("Decryption failed:", error);
    return null;
  }
}
