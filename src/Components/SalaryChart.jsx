
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, LabelList, ResponsiveContainer } from "recharts";

const data = [
  {
    name: "Program Lead",
    bar1: 155,
    bar2: 98,
    bar3: 130,
  },
  {
    name: "Program Manager",
    bar1: 155,
    bar2: 100,
    bar3: 120,
  },
  {
    name: "Program Director",
    bar1: 155,
    bar2: 120,
    bar3: 100,
  },
];

export default function SalaryChart() {
  return (
    <div className="w-full h-[500px] bg-white rounded-lg p-6">
      <ResponsiveContainer width="100%" height="100%">
        <BarChart
          data={data}
          margin={{ top: 30, right: 20, left: 20, bottom: 40 }}
          barCategoryGap="15%"
        >
          <CartesianGrid strokeDasharray="3 3" vertical={false} horizontal={false} />
          <XAxis
            dataKey="name"
            tick={{ fill: "#333", fontSize: 14, fontWeight: 600 }}
            axisLine={{ stroke: "#2E3192", strokeWidth: 2 }}
            tickLine={false}
          />
          <YAxis hide />
          <Tooltip cursor={{ fill: "rgba(0,0,0,0.05)" }} />
          <Bar dataKey="bar1" fill="#0D6EFD" radius={[8, 8, 0, 0]}>
            <LabelList dataKey="bar1" position="top" formatter={(v) => `Rs. ${v}K`} fill="#444" fontSize={12} />
          </Bar>
          <Bar dataKey="bar2" fill="#009CDE" radius={[8, 8, 0, 0]}>
            <LabelList dataKey="bar2" position="top" formatter={(v) => `Rs. ${v}K`} fill="#444" fontSize={12} />
          </Bar>
          <Bar dataKey="bar3" fill="#69E3E3" radius={[8, 8, 0, 0]}>
            <LabelList dataKey="bar3" position="top" formatter={(v) => `Rs. ${v}K`} fill="#444" fontSize={12} />
          </Bar>
        </BarChart>
      </ResponsiveContainer>
    </div>
  );
}
