const STATUS_CLASSES = {
  pending: "p-2 rounded-lg mb-3 text-sm bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200",
  success: "p-2 rounded-lg mb-3 text-sm bg-green-50 dark:bg-green-950/40 text-green-700 dark:text-green-400",
  error: "p-2 rounded-lg mb-3 text-sm bg-red-50 dark:bg-red-950/40 text-red-700 dark:text-red-400",
};

document.addEventListener("click", async function (event) {
  if (event.target && event.target.id === "triggerApiBtn") {
    const status = document.getElementById("apiStatus");

    status.className = STATUS_CLASSES.pending;
    status.classList.remove("hidden");
    status.textContent = "Sending...";

    try {
      const response = await fetch(`api/test-api`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
        },
        body: JSON.stringify({ extraData: "test" }),
      });

      const result = await response.json();

      if (response.ok && result.success) {
        status.className = STATUS_CLASSES.success;
        status.textContent = result.message;
      } else {
        status.className = STATUS_CLASSES.error;
        status.textContent = result.message || "Failed";
      }
    } catch (err) {
      status.className = STATUS_CLASSES.error;
      status.textContent = "Error: " + err.message;
    }
  }
});

document.addEventListener("submit", async function (e) {
  const form = e.target;

  if (form.id !== "testMailForm") return;

  e.preventDefault();

  const statusBox = document.getElementById("mailStatus");

  const formData = {
    to: form.querySelector('input[name="to"]').value,
    subject: form.querySelector('input[name="subject"]').value,
    title: form.querySelector('input[name="title"]').value,
    body: form.querySelector('textarea[name="body"]').value,
  };

  statusBox.className = STATUS_CLASSES.pending;
  statusBox.textContent = "Sending...";
  statusBox.classList.remove("hidden");

  try {
    const response = await fetch(`api/send-mail`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
          .content,
      },
      body: JSON.stringify(formData),
    });

    const result = await response.json();

    if (response.ok && result.success) {
      statusBox.className = STATUS_CLASSES.success;
      statusBox.textContent = result.message;
      form.reset();
    } else {
      statusBox.className = STATUS_CLASSES.error;
      statusBox.textContent = result.message || "Failed to send mail.";
    }
  } catch (err) {
    statusBox.className = STATUS_CLASSES.error;
    statusBox.textContent = "Error: " + err.message;
  }

  setTimeout(() => statusBox.classList.add("hidden"), 5000);
});
