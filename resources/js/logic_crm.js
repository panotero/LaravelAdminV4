window.initCrmLogic = function initCrmLogic() {
  // ============================================================
  // STATE
  // ============================================================

  let leadUUID = "";
  let leadsArray = [];
  let leadInfo = {};

  // ============================================================
  // CONSTANTS
  // ============================================================

  const STATUS_BADGE = {
    LEAD: "bg-gray-100 text-gray-700",
    QUALIFIED: "bg-indigo-100 text-indigo-700",
    OPPORTUNITY: "bg-purple-100 text-purple-700",
    NEGOTIATION: "bg-amber-100 text-amber-700",
    WIN: "bg-green-100 text-green-700",
    LOST: "bg-red-100 text-red-700",
    DEFAULT: "bg-zinc-100 text-zinc-700",
  };

  const PROPOSAL_STATUS = {
    APPROVED: 2,
  };

  // ============================================================
  // DOM REFS
  // ============================================================

  const addActivityBtn = document.getElementById("addActivityBtn");
  const activityDropdown = document.getElementById("activityDropdown");
  const activityDescInput = document.getElementById("activityDescriptionInput");
  const activityAttachmentInput = document.getElementById(
    "activityAttachmentInput",
  );
  const activityTypeInput = document.getElementById("activityTypeInput");
  const activityStatusInput = document.getElementById("activityStatusInput");
  const saveActivityBtn = document.getElementById("saveActivityBtn");
  const cancelActivityBtn = document.getElementById("cancelActivityBtn");

  const addNoteBtn = document.getElementById("addNoteBtn");
  const noteDropdown = document.getElementById("noteDropdown");
  const noteInput = document.getElementById("noteInput");
  const saveNoteBtn = document.getElementById("saveNoteBtn");
  const cancelNoteBtn = document.getElementById("cancelNoteBtn");

  const editContactBtn = document.getElementById("editContactBtn");
  const editContactInfoDropdown = document.getElementById(
    "editContactInfoDropdown",
  );
  const saveContactInfoBtn = document.getElementById("saveContactInfoBtn");
  const cancelContactInfoBtn = document.getElementById("cancelContactInfoBtn");

  // ============================================================
  // HELPERS
  // ============================================================

  function getStatusBadgeClass(status) {
    return STATUS_BADGE[status] ?? STATUS_BADGE.DEFAULT;
  }

  function openDropdown(dropdown) {
    dropdown.classList.remove("hidden");
  }

  function closeDropdown(dropdown) {
    dropdown.classList.add("hidden");
  }

  function emptyState(message) {
    return `
        <div class="w-full py-3 rounded-md text-center">
            <p class="text-xs font-medium text-zinc-400">${message}</p>
        </div>`;
  }

  // ============================================================
  // API
  // ============================================================

  async function getleadcount() {
    const leads = await apiCall({
      mode: "GET",
      url: "/api/crm/leads",
    });
    return leads;
  }

  async function getStatuses() {
    const statuses = await apiCall({
      mode: "GET",
      url: "/api/crm/getCrmStatus",
    });

    document.querySelectorAll(".statusDropDown").forEach((dropdown) => {
      dropdown.innerHTML = [
        `<option value="">Select Status</option>`,
        ...statuses.data.map(
          (s) => `<option value="${s.id}">${s.status}</option>`,
        ),
      ].join("");
    });
  }

  // ============================================================
  // RENDER — TABLE
  // ============================================================

  function renderTable() {
    const thead = [
      {
        title: "Contact",
        key: "contact_name",
      },
      {
        title: "Company",
        key: "company.company_name",
      },
      {
        title: "Email",
        key: "email",
      },
      {
        title: "Mobile",
        key: "mobile",
      },
      {
        title: "Status",
        key: "crm_status.status",
      },
      {
        title: "Assigned To",
        key: "user.name",
      },
      {
        title: "Created",
        key: "created_at",
        render: (row) => formatDateTime(row.created_at),
      },
    ];
    const table = renderRemoteTable({
      url: "/api/crm/leads",
      tableId: "tableCrm",
      afterRenderFunction: handleClick,
      thead: thead,
    });

    const OPEN_MODAL_STATUSES = ["OPPORTUNITY", "NEGOTIATION", "WIN", "LOST"];

    function handleClick(row) {
      row.addEventListener("click", function () {
        const data = JSON.parse(row.dataset.row);
        const status = data.crm_status?.status;

        if (OPEN_MODAL_STATUSES.includes(status)) {
          loadLeadInfo(data.uuid);
          initModal({ modalId: "LeadInfoModal" });
        } else {
          window.crmLeadFormUuid = data.uuid;
          loadPage({ title: "Edit Lead", link: "/page_crmLeadForm" });
        }
      });

      return table;
    }

    return table;
  }

  document.querySelectorAll(".statusBtn").forEach((btn) => {
    btn.addEventListener("click", function () {
      document
        .querySelectorAll(".statusBtn")
        .forEach((card) => card.classList.remove("ring-2", "ring-orange-500"));

      this.classList.add("ring-2", "ring-orange-500");

      const status = this.dataset.status;

      renderTable().setFilter("status", status);
    });
  });

  // ============================================================
  // RENDER — COUNTS
  // ============================================================

  async function renderCounts() {
    const lead = await getleadcount();
    const counts = lead.status_counts;

    const COUNT_MAP = {
      ALL: "countALL",
      LEAD: "countLead",
      QUALIFIED: "countQualified",
      OPPORTUNITY: "countOpportunity",
      NEGOTIATION: "countNegotiation",
      WIN: "countWin",
      LOST: "countLose",
    };

    // leads.forEach((row) => {
    //   const status = row.status.status;
    //   if (counts.hasOwnProperty(status)) counts[status]++;
    // });

    Object.entries(COUNT_MAP).forEach(([key, elementId]) => {
      const el = document.getElementById(elementId);
      if (el) el.innerText = counts[key] ?? 0;
    });
  }

  // ============================================================
  // RENDER — LEAD INFO
  // ============================================================

  async function loadLeadInfo(uuid) {
    const loader = loadingLine();
    [
      "#leadCompanyName",
      "#leadStatus",
      "#leadContactName",
      "#leadPosition",
      "#leadEmail",
      "#leadMobile",
      "#leadSource",
      "#leadAssignedTo",
      "#leadCompanyNameFull",
      "#leadCompanyAddress",
      "#leadTypeOfBusiness",
      "#leadEstimatedValue",
      "#leadCreatedAt",
      "#leadExpectedCloseDate",
      "#noteContainer",
      "#activityContainer",
      "#containerListContainer",
    ].forEach((id) => $(id).html(loader));
    document.getElementById("proposalContainer").innerHTML = loader;
    leadUUID = uuid;

    const response = await apiCall({
      mode: "GET",
      url: `/api/crm/leads/${uuid}`,
    });

    if (!response.success) {
      showMessage({
        status: "error",
        title: "Error Fetching Lead",
        message:
          "There is an error fetching your information. Please contact the system administrator.",
      });
      return;
    }

    leadInfo = response.data;
    const lead = response.data;
    const company = lead.company ?? {};
    const value = Number(lead?.estimated_value || 0);
    const statusClass = getStatusBadgeClass(lead.crm_status.status);

    const opportunityBtn = document.getElementById("createClientMasterBtn");
    const isOpportunityOrLater = !["LEAD", "QUALIFIED"].includes(
      lead.crm_status.status,
    );
    opportunityBtn.classList.toggle("hidden", !isOpportunityOrLater);
    opportunityBtn.onclick = function () {
      window.clientMasterFormUuid = null;
      window.clientMasterFormLeadId = lead.id;
      window.clientMasterFormPrefill = {
        company_name: company.company_name ?? "",
        registered_address: company.company_address ?? "",
        contact_number_1: lead.mobile ?? "",
        industry: company.type_of_business ?? "",
      };
      loadPage({
        title: "New Client Master Data",
        link: "/page_clientMasterForm",
      });
    };

    $("#leadCompanyName").html((company.company_name ?? "").toUpperCase());
    $("#leadStatus").html(`
        <span class="px-3 py-1 text-xs font-semibold rounded-full ${statusClass}">
            ${lead.crm_status.status}
        </span>`);
    $("#leadContactName").html(lead.contact_name ?? "-");
    $("#leadPosition").html(lead.position ?? "-");
    $("#leadEmail").html(lead.email ?? "-");
    $("#leadMobile").html(lead.mobile ?? "-");
    $("#leadSource").html(lead.source ?? "-");
    $("#leadAssignedTo").html(lead.user?.name ?? "-");
    $("#leadCompanyNameFull").html(company.company_name ?? "-");
    $("#leadCompanyAddress").html(company.company_address ?? "-");
    $("#leadTypeOfBusiness").html(company.type_of_business ?? "-");
    $("#leadEstimatedValue").html(`₱${value.toLocaleString()}`);
    $("#leadCreatedAt").html(formatDateTime(lead.created_at) ?? "-");
    $("#leadExpectedCloseDate").html(
      lead.expected_close_date ? formatDateTime(lead.expected_close_date) : "-",
    );

    $("#contactName").val(lead.contact_name ?? "");
    $("#contactEmail").val(lead.email ?? "");
    $("#contactMobile").val(lead.mobile ?? "");
    $("#activityStatusInput").val(lead.status ?? "");

    renderActivity(lead.activities);
    renderNotes(lead.notes);
    renderProposals(lead.proposals);
    renderContainers(lead.containers);
  }

  // ============================================================
  // RENDER — PROPOSALS
  // ============================================================

  function renderProposals(proposals) {
    const container = document.getElementById("proposalContainer");

    if (!proposals.length) {
      container.innerHTML = emptyState(
        "There's no proposal yet. Create one now!",
      );
      return;
    }

    container.innerHTML = proposals
      .map((proposal) => {
        const statusClass = getStatusBadgeClass(proposal.status.status);
        const isApproved = proposal.status.id === PROPOSAL_STATUS.APPROVED;
        const downloadUrl = `/createpdf/${proposal.id}`;
        const downloadClass = isApproved
          ? "bg-orange-600 hover:bg-orange-700"
          : "pointer-events-none opacity-50 cursor-not-allowed bg-gray-400";

        return `
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 w-full flex justify-between items-center gap-4">

                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full ${statusClass}"></span>
                            <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">${proposal.status.status}</p>
                        </div>
                        <h2 class="text-sm font-medium text-zinc-800 dark:text-zinc-100">${proposal.code}</h2>
                        <p class="text-[11px] text-zinc-400">${formatDateTime(proposal.created_at)}</p>
                    </div>

                    <a href="${downloadUrl}" target="_blank"
                        class="shrink-0 ${downloadClass} text-white w-8 h-8 flex items-center justify-center rounded-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M7.5 10.5l4.5 4.5m0 0l4.5-4.5m-4.5 4.5V3" />
                        </svg>
                    </a>

                </div>`;
      })
      .join("");
  }
  function renderContainers(containers) {
    const container = document.getElementById("containerListContainer");

    if (!containers || !containers.length) {
      container.innerHTML = emptyState("No container requirements added yet.");
      return;
    }

    const TYPE_LABELS = {
      CV: "Container Van",
      FR: "Flatrack",
      RF: "Reefer Van",
      LC: "Loose Cargo",
      RC: "Rolling Cargo",
    };

    container.innerHTML = containers
      .map((c) => {
        const origin = c.origin_port ? `${c.origin_port.code}` : "-";
        const destination = c.destination_port
          ? `${c.destination_port.code}`
          : "-";
        const typeLabel = TYPE_LABELS[c.container_type] ?? c.container_type;
        const extra = [
          c.container_class?.class,
          c.container_size?.size,
          c.quantity ? `Qty: ${c.quantity}` : null,
          c.dangerous_cargo ? "DG" : null,
        ]
          .filter(Boolean)
          .join(" · ");

        return `
                <div class="border border-zinc-200 dark:border-zinc-700 rounded-md p-2 w-full flex flex-col gap-0.5">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-100">${typeLabel}</span>
                        <span class="text-[11px] text-zinc-400">${origin} &rarr; ${destination}</span>
                    </div>
                    ${extra ? `<p class="text-[11px] text-zinc-500">${extra}</p>` : ""}
                </div>`;
      })
      .join("");
  }

  // ============================================================
  // RENDER — ACTIVITIES
  // ============================================================

  function renderActivity(activities) {
    const container = document.getElementById("activityContainer");

    if (!activities || !activities.length) {
      container.innerHTML = emptyState("No activities found");
      return;
    }

    container.innerHTML = activities
      .map(
        (activity) => `
            <div class="bg-white dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800 rounded-lg px-2.5 py-2 w-full">
                <div class="flex justify-between items-baseline gap-2">
                    <p class="text-[10px] font-semibold text-zinc-400 uppercase tracking-wide truncate">${activity.type}</p>
                    <p class="text-[10px] text-zinc-400 shrink-0">${formatDateTime(activity.created_at)}</p>
                </div>
                <p class="text-xs text-zinc-800 dark:text-zinc-100 mt-0.5 leading-snug">${activity.description}</p>
                <p class="text-[10px] text-zinc-400 mt-0.5">${activity.user.name}</p>
            </div>`,
      )
      .join("");
  }

  // ============================================================
  // RENDER — NOTES
  // ============================================================

  function renderNotes(notes) {
    const container = document.getElementById("noteContainer");

    if (!notes || !notes.length) {
      container.innerHTML = emptyState("No notes found");
      return;
    }

    container.innerHTML = notes
      .map(
        (note) => `
            <div class="bg-white dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800 rounded-lg px-2.5 py-2 w-full">
                <div class="flex justify-between items-baseline gap-2">
                    <p class="text-[10px] font-semibold text-zinc-500 truncate">${note.user.name}</p>
                    <p class="text-[10px] text-zinc-400 shrink-0">${formatDateTime(note.created_at)}</p>
                </div>
                <p class="text-xs text-zinc-700 dark:text-zinc-300 mt-0.5 leading-snug">${note.note}</p>
            </div>`,
      )
      .join("");
  }

  // ============================================================
  // LEAD FORM
  // ============================================================

  function getLeadFormData() {
    return {
      contact_name: $("#contact_name").val(),
      email: $("#email").val(),
      mobile: $("#mobile").val(),
      source: $("#source").val(),
      estimated_value: $("#estimated_value").val(),
      expected_close_date: $("#expected_close_date").val(),
    };
  }

  $("#saveLeadBtn").on("click", async function (e) {
    e.preventDefault();

    const form = $("#leadForm")[0];
    const estValue = form.elements["est_value"].value.replace(/,/g, "");
    const formData = new FormData();

    formData.append("contact_name", form.contact_name.value);
    formData.append("mobile", form.mobile.value);
    formData.append("email", form.email.value);
    formData.append("company_name", form.company_name.value);
    formData.append("position", form.position.value);
    formData.append("status", form.status.value);
    formData.append("est_value", estValue);
    formData.append("source", form.source.value);
    formData.append("notes", form.notes.value);

    const response = await apiCall({
      mode: "POST",
      isJson: false,
      payload: formData,
      url: "/api/crm/leads",
      button: document.getElementById("saveLeadBtn"),
    });

    if (!response.success) {
      showMessage({
        status: "error",
        title: "Error Saving Lead",
        message:
          "There is an error saving your information. Please contact the system administrator.",
      });
      return;
    }

    showMessage({ status: "success", title: "Lead saved successfully!" });

    console.log(leadcount);
    renderTable().load(1);
    renderCounts();
    clearInputs();
    closeSideModal("LeadDetailsSideModal");
  });

  $(document).on("click", "#btnEditLead", function () {
    $(".lead-input").prop("readonly", false);
    $("#btnEditLead").addClass("hidden");
  });

  $(document).on("input change", ".lead-input", function () {
    const changed =
      JSON.stringify(getLeadFormData()) !== JSON.stringify(originalLeadData);
    $("#btnSaveLead").toggleClass("hidden", !changed);
  });

  $(document).on("click", "#btnSaveLead", async function () {
    const response = await apiCall({
      mode: "PUT",
      isJson: true,
      payload: JSON.stringify(getLeadFormData()),
      url: "/api/crm/leads",
      button: document.getElementById("btnSaveLead"),
    });

    if (!response.success) {
      showMessage({
        status: "error",
        title: "Error Updating Lead",
        message:
          "There is an error updating your information. Please contact the system administrator.",
      });
      return;
    }

    toastr.success("Lead updated successfully");
    originalLeadData = getLeadFormData();
    $(".lead-input").prop("readonly", true);
    $("#btnSaveLead").addClass("hidden");
    $("#btnEditLead").removeClass("hidden");
  });

  // ============================================================
  // STATUS FILTER BUTTONS
  // ============================================================

  // ============================================================
  // TABLE ROW CLICK
  // ============================================================

  $(document).on("click", "#crmTable tbody tr", function () {
    leadUUID = $(this).data("uuid");
    window.uuid = leadUUID;
    loadLeadInfo();
  });

  // ============================================================
  // ACTIVITY EVENTS
  // ============================================================

  addActivityBtn.addEventListener("click", () =>
    openDropdown(activityDropdown),
  );

  cancelActivityBtn.addEventListener("click", () => {
    activityStatusInput.value = "";
    activityTypeInput.value = "";
    activityDescInput.value = "";
    activityAttachmentInput.value = "";
    closeDropdown(activityDropdown);
  });

  saveActivityBtn.addEventListener("click", async function () {
    const attachment = activityAttachmentInput.files[0];
    const formData = new FormData();

    formData.append("leadUUId", leadUUID);
    formData.append("status", activityStatusInput.value);
    formData.append("type", activityTypeInput.value);
    formData.append("activity", activityDescInput.value);

    if (activityAttachmentInput.files.length > 0) {
      formData.append("attachment", activityAttachmentInput.files[0]);
    }
    const response = await apiCall({
      mode: "POST",
      isJson: false,
      payload: formData,
      url: "/api/crm/activity",
      button: saveActivityBtn,
    });

    if (!response.success) {
      showMessage({ status: "error", title: "Error Saving Activity" });
      return;
    }

    showMessage({ status: "success", title: "Activity Saved!" });
    activityStatusInput.value = "";
    activityTypeInput.value = "";
    activityDescInput.value = "";
    closeDropdown(activityDropdown);
    reloadCrmData();
  });

  // ============================================================
  // NOTE EVENTS
  // ============================================================

  addNoteBtn.addEventListener("click", () => openDropdown(noteDropdown));

  cancelNoteBtn.addEventListener("click", () => {
    noteInput.value = "";
    closeDropdown(noteDropdown);
  });

  saveNoteBtn.addEventListener("click", async function () {
    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload: { leadUUId: leadUUID, note: noteInput.value },
      url: "/api/crm/note",
      button: saveNoteBtn,
    });

    if (!response.success) {
      showMessage({ status: "error", title: "Error Saving Note" });
      return;
    }

    showMessage({ status: "success", title: "Note saved!" });
    noteInput.value = "";
    closeDropdown(noteDropdown);
    loadLeadInfo();
  });

  // ============================================================
  // CONTACT INFO EVENTS
  // ============================================================

  editContactBtn.addEventListener("click", () =>
    openDropdown(editContactInfoDropdown),
  );

  cancelContactInfoBtn.addEventListener("click", () => {
    $("#saveContactInfoBtn").removeClass("hidden");
    closeDropdown(editContactInfoDropdown);
  });

  saveContactInfoBtn.addEventListener("click", async function () {
    const response = await apiCall({
      mode: "PUT",
      isJson: true,
      payload: {
        leadUUId: leadUUID,
        contact_name: $("#contactName").val(),
        contact_mobile: $("#contactMobile").val(),
        contact_email: $("#contactEmail").val(),
      },
      url: `/api/crm/leads/${leadUUID}`,
      button: saveContactInfoBtn,
    });

    if (!response.success) {
      showMessage({ status: "error", title: "Error Updating Contact" });
      return;
    }

    showMessage({ status: "success", title: "Contact Updated!" });
    closeDropdown(editContactInfoDropdown);
    loadLeadInfo();
  });

  $(".editContactDropdown").on("change", function () {
    $("#saveContactInfoBtn").removeClass("hidden");
  });

  // ============================================================
  // CLICK OUTSIDE — CLOSE DROPDOWNS
  // ============================================================

  window.addEventListener("click", (e) => {
    if (
      !addActivityBtn.contains(e.target) &&
      !activityDropdown.contains(e.target)
    )
      closeDropdown(activityDropdown);

    if (!addNoteBtn.contains(e.target) && !noteDropdown.contains(e.target))
      closeDropdown(noteDropdown);

    if (
      !editContactBtn.contains(e.target) &&
      !editContactInfoDropdown.contains(e.target)
    )
      closeDropdown(editContactInfoDropdown);
  });

  // ============================================================
  // NEW PROPOSAL BUTTON
  // ============================================================

  document
    .getElementById("createClientMasterBtn")
    .addEventListener("click", function () {
      // leadInfo is already populated by loadLeadInfo() whenever the
      // LeadInfoModal is open, so we can prefill straight from it.
      window.clientMasterFormUuid = null;
      window.clientMasterFormLeadId = leadInfo?.id ?? null;
      window.clientMasterFormPrefill = {
        company_name: leadInfo?.company?.company_name ?? "",
        registered_address: leadInfo?.company?.company_address ?? "",
        contact_number_1: leadInfo?.mobile ?? "",
      };

      loadPage({
        title: "New Client Master Data",
        link: "/page_clientMasterForm",
      });
    });

  // ============================================================
  // PUBLIC API
  // ============================================================

  window.reloadCrmData = function () {
    loadLeadInfo(leadUUID);
    updateLeadDetails();
  };

  // ============================================================
  // INIT
  // ============================================================

  async function updateLeadDetails() {
    renderTable().load(1);
    renderCounts();
  }

  async function initializePage() {
    updateLeadDetails();
    getStatuses();

    document
      .getElementById("btnNewLead")
      .addEventListener("click", function () {
        window.crmLeadFormUuid = null;
        loadPage({ title: "New Lead", link: "/page_crmLeadForm" });
      });
  }

  initializePage();
};
