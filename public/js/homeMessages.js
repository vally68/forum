document.addEventListener("DOMContentLoaded", () => {
  const path = document.getElementById("serpent-path");
  const container = document.getElementById("messages-path");
  if (!path || !container) return;

  const bubbles = Array.from(container.querySelectorAll(".message-bubble"));
  if (bubbles.length === 1) return;

  // attendre que le SVG soit rendu
  requestAnimationFrame(() => {
    const pathLength = path.getTotalLength();
    const totalMessages = bubbles.length;
    const spacing = pathLength / totalMessages;
    const speed = 1.2;

    // Récupérer les dimensions réelles du SVG et du viewBox
    const svg = path.closest("svg");
    const viewBox = svg.viewBox.baseVal;
    const svgRect = svg.getBoundingClientRect();

    // Rapport de conversion coordonnées SVG → coordonnées écran
    const scaleX = svgRect.width / viewBox.width;
    const scaleY = svgRect.height / viewBox.height;

    // Centrer les bulles sur la ligne jaune
    const offsetY = -svgRect.height * 0.002; // petit ajustement vertical

    const items = bubbles.map((el, i) => ({
      el,
      pos: i * spacing,
    }));

    function animate() {
      items.forEach(item => {
        item.pos += speed;
        if (item.pos > pathLength) item.pos = 0;

        const pt = path.getPointAtLength(item.pos);
        const nextPt = path.getPointAtLength((item.pos + 2) % pathLength);
        const angle = Math.atan2(nextPt.y - pt.y, nextPt.x - pt.x) * 180 / Math.PI;

        // Conversion coordonnées SVG → coordonnées pixels
        const x = pt.x * scaleX;
        const y = pt.y * scaleY + offsetY;

        item.el.style.transform = `translate(${x}px, ${y}px) rotate(${angle}deg) translate(-50%, -50%)`;
      });

      requestAnimationFrame(animate);
    }

    animate();
  });
});
