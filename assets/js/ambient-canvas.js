/**
 * PT Nihon Plast Indonesia - Ambient Automotive Tech Particle Canvas
 * 100% Free & Open Source Vanilla JS | Lightweight & Zero Dependencies
 */
(function () {
    const canvas = document.getElementById('ambientCanvas');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    let width = 0;
    let height = 0;
    let particles = [];
    let animationFrameId = null;
    let isVisible = true;

    const PARTICLE_COUNT = window.innerWidth < 768 ? 24 : 48;
    const MAX_DISTANCE = 140;

    function resize() {
        const parent = canvas.parentElement;
        if (!parent) return;
        width = canvas.width = parent.offsetWidth;
        height = canvas.height = parent.offsetHeight;
    }

    class Particle {
        constructor() {
            this.reset(true);
        }

        reset(initial = false) {
            this.x = Math.random() * width;
            this.y = initial ? Math.random() * height : height + 10;
            this.vx = (Math.random() - 0.5) * 0.45;
            this.vy = -(Math.random() * 0.35 + 0.15);
            this.radius = Math.random() * 1.8 + 1;
            this.alpha = Math.random() * 0.55 + 0.2;
            this.pulseSpeed = Math.random() * 0.02 + 0.01;
            this.pulsePhase = Math.random() * Math.PI * 2;
        }

        update() {
            this.x += this.vx;
            this.y += this.vy;
            this.pulsePhase += this.pulseSpeed;

            if (this.x < -10 || this.x > width + 10 || this.y < -10) {
                this.reset(false);
            }
        }

        draw() {
            const currentAlpha = this.alpha * (0.7 + 0.3 * Math.sin(this.pulsePhase));
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(16, 185, 129, ${currentAlpha})`;
            ctx.shadowBlur = 8;
            ctx.shadowColor = 'rgba(16, 185, 129, 0.4)';
            ctx.fill();
            ctx.shadowBlur = 0;
        }
    }

    function init() {
        resize();
        particles = [];
        for (let i = 0; i < PARTICLE_COUNT; i++) {
            particles.push(new Particle());
        }
    }

    function drawLines() {
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const dist = Math.sqrt(dx * dx + dy * dy);

                if (dist < MAX_DISTANCE) {
                    const lineAlpha = (1 - dist / MAX_DISTANCE) * 0.18;
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.strokeStyle = `rgba(110, 231, 183, ${lineAlpha})`;
                    ctx.lineWidth = 0.8;
                    ctx.stroke();
                }
            }
        }
    }

    function animate() {
        if (!isVisible) return;
        ctx.clearRect(0, 0, width, height);

        for (let i = 0; i < particles.length; i++) {
            particles[i].update();
            particles[i].draw();
        }
        drawLines();

        animationFrameId = requestAnimationFrame(animate);
    }

    // Performance optimization: pause when offscreen
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            isVisible = entry.isIntersecting;
            if (isVisible) {
                if (!animationFrameId) animate();
            } else {
                if (animationFrameId) {
                    cancelAnimationFrame(animationFrameId);
                    animationFrameId = null;
                }
            }
        });
    }, { threshold: 0.1 });

    window.addEventListener('resize', () => {
        resize();
    });

    init();
    observer.observe(canvas.parentElement || canvas);
    animate();
})();
