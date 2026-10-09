import Navbar from "@/Components/Navbar";
import Herosection from "@/Components/Herosection";
import Footer from "@/Components/Footer";

export default function Home() {
  return (
    <>
      <div className="antialiased bg-gray-50 text-gray-800">
        <Navbar />
        <Herosection />
        <Footer />
      </div>
    </>
  );
}
