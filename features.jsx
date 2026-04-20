/* const { FaList, FaReceipt, FaBell } = ReactIcons.fa; */

const features = [
    {
        title: "Shopping List",
        subtitle: "Create & Share Lists",
        description:
        "Build and share shopping lists with family, friends, or roomates. Never forget an item again.",
        icon: "📝"
    }, 
    {
        title: "Scan Reciepts",
        subtitle: "OCR Auto-Tracking",
        description:
        "Easily track your spending by scanning your receipts. OCR technology can help organize purchases automatically.",
        icon: "🧾"
    },
    {
        title: "Budget Alerts",
        subtitle: "Real-time Warnings",
        description:
        "Set custom budget alerts and get notified when you're approaching or exceeding your budget.",
        icon: "🔔"
    },
    ];

function FeatureCard({ title, subtitle, description, icon }) {
  const [hovered, setHovered] = React.useState(false);

  return (
    <div
      className="feature"
      onMouseEnter={() => setHovered(true)}
      onMouseLeave={() => setHovered(false)}
    >
      <div
        className="circle"
        style={{
          backgroundColor: hovered ? "#5d91a8" : "#d3d3d3",
          transition: "background-color 0.2s ease",
        }}
      >
        {icon}
      </div>

      <h3>{title}</h3>
      <p>{hovered ? description : subtitle}</p>
    </div>
  );
}

function Features() {
  return (
    <>
      {features.map((feature, index) => (
        <FeatureCard
          key={index}
          title={feature.title}
          subtitle={feature.subtitle}
          description={feature.description}
          icon={feature.icon}
        />
      ))}
    </>
  );
}

const root = ReactDOM.createRoot(document.getElementById("feature-root"));
root.render(<Features />);