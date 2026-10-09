import React from "react";

function Loader() {
  return (
    <div className="loader">
      {[0, 1, 2, 3, 4].map((i) => (
        <div
          key={i}
          className="loader_dot"
          style={{ "--index": i }}
        ></div>
      ))}
    </div>
  );
}

export default Loader;
