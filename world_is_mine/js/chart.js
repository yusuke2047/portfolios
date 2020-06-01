// HTML内のレーダチャートを描画するための要素を取得
const ctx = document.getElementById("myRadarChart");
// レーダチャートに関する設定
const myRadarChart = new Chart(ctx, {
  type: 'radar',
  data: {
    labels: ['JANKEN', 'OSERO', 'SLOT', 'MEMORY', 'TEN SECONDS'],
    datasets: [{
      backgroundColor: "rgba(255,0,0,0.4)",
      data: [userProgress.jankenProgress, userProgress.oseroProgress, userProgress.slotProgress, userProgress.memoryProgress,userProgress.tenSecondsProgress],
      pointStyle: "line"
    }]
  },
  options: {
    legend: {
      display: false
    },
    scale: {
      ticks: {
        backdropColor: "transparent",
        fontColor: "#00f",
        fontSize: 16,
        max: 100,
        min: 0,
        stepSize: 20
      },
      // 軸（放射軸）
      angleLines: {
        color: "#c0c",
        lineWidth: 0.8
      },
      // 補助線（目盛の線)
      gridLines: {
        color: "#0f0",
        lineWidth: 0.1
      },
      // 軸のラベル
      pointLabels: {
        fontSize: 14,
        fontColor: "#008000",
      }
    }
  }
});
