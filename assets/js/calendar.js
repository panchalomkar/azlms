const calendarDates = document.getElementById("calendarDates");
const monthYear = document.getElementById("calendarMonthYear");
const prevMonthBtn = document.getElementById("prevMonthBtn");
const nextMonthBtn = document.getElementById("nextMonthBtn");

const sampleData = {
  // Add your sample attendance data (key: yyyy-mm-dd)
  "2025-08-01": { class: "MA201-110", time: "Room TBA", hours: 4 },
  "2025-08-02": { class: "MA201-110", time: "Room TBA", hours: 4 },
  "2025-08-03": { class: "MA201-110", time: "Half Day", hours: 2 },
  "2025-08-10": {
    class: "MA201-110",
    time: "Cancelled",
    hours: 0,
    cancelled: true,
  },
  "2025-08-14": {
    class: "MA201-110",
    time: "Room TBA",
    hours: 4,
    highlight: true,
  },
};

let today = new Date();

function renderCalendar(date) {
  const year = date.getFullYear();
  const month = date.getMonth();

  const firstDay = new Date(year, month, 1);
  const startDay = (firstDay.getDay() + 6) % 7; // Make Monday start
  const totalDays = new Date(year, month + 1, 0).getDate();

  calendarDates.innerHTML = "";
  monthYear.textContent = date.toLocaleString("default", {
    month: "short",
    year: "numeric",
  });

  for (let i = 0; i < startDay; i++) {
    calendarDates.innerHTML += `<div class="calendar-cell empty"></div>`;
  }

  for (let day = 1; day <= totalDays; day++) {
    const dateStr = `${year}-${String(month + 1).padStart(2, "0")}-${String(
      day
    ).padStart(2, "0")}`;
    const data = sampleData[dateStr];

    if (data) {
      const className = data.cancelled
        ? "cancelled"
        : data.highlight
        ? "highlight"
        : "active";

      calendarDates.innerHTML += `
          <div class="calendar-cell ${className}">
            <div class="cal-date">${day}</div>
            <div class="cal-info">
              <div>${data.class}</div>
              <div>${data.time}</div>
              <div class="hours">${data.hours} HRS</div>
            </div>
          </div>
        `;
    } else {
      calendarDates.innerHTML += `<div class="calendar-cell">${day}</div>`;
    }
  }
}

prevMonthBtn.onclick = () => {
  today.setMonth(today.getMonth() - 1);
  renderCalendar(today);
};
nextMonthBtn.onclick = () => {
  today.setMonth(today.getMonth() + 1);
  renderCalendar(today);
};

renderCalendar(today);
