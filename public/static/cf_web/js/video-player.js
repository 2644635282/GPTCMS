(() => {
	document.querySelectorAll(".video-control").forEach((control) => {
		control.addEventListener("click", () => {
			const { targetVideo } = control.dataset;
			const myVideo = document.getElementById(targetVideo);
			if (myVideo.paused) {
				myVideo.play();
			} else {
				myVideo.pause();
			}
		});
	});

	document.querySelectorAll(".video-container").forEach((video) => {
		window.addEventListener("click", (e) => {
			const control = video.querySelector(".video-control");
			const { targetVideo } = control.dataset;
			const myVideo = document.getElementById(targetVideo);
			const play = control.querySelector(".play-icon");
			const pause = control.querySelector(".pause-icon");

			myVideo.addEventListener("playing", () => {
				control.classList.remove("block");
				control.classList.add("hidden");
				play.classList.remove("block");
				play.classList.add("hidden");
				pause.classList.remove("hidden");
				pause.classList.add("block");
			});
			myVideo.addEventListener("pause", () => {
				control.classList.remove("hidden");
				control.classList.add("block");
				play.classList.remove("hidden");
				play.classList.add("block");
				pause.classList.remove("block");
				pause.classList.add("hidden");
			});
		});
	});

	document.querySelectorAll(".video-modal").forEach((modal) => {
		modal.addEventListener("click", () => {
			const control = modal.querySelector(".video-control");
			const { targetVideo } = control.dataset;
			const myVideo = document.getElementById(targetVideo);
			setTimeout(() => {
				if (modal.style.display) myVideo.pause();
			}, 600);
		});
	});
})();
