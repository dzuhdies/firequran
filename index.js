document.addEventListener("DOMContentLoaded", function () {
    if (!userId) {
        Swal.fire({
            icon: "warning",
            title: "Anda belum login",
            text: "Silakan login terlebih dahulu.",
            confirmButtonText: "OK"
        }).then(() => {
            window.location.href = "login.php";
        });
        return;
    }
});

 
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
}


function showModal() {
    const modal = document.getElementById('modal');
    const overlay = document.getElementById('overlay');
    modal.classList.add('active');
    overlay.classList.add('active');
}

 
function hideModal() {
    const modal = document.getElementById('modal');
    const overlay = document.getElementById('overlay');
    modal.classList.remove('active');
    overlay.classList.remove('active');
}

function addProgress() {
    const input = document.getElementById("progressInput");
    const pages = parseInt(input.value);

    if (pages > 0) {
        fetch("addprogres.php", {
            method: "POST",
            body: new URLSearchParams({ pages: pages }),
            headers: { "Content-Type": "application/x-www-form-urlencoded" }
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: `Progres ${pages} halaman telah ditambahkan.`,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: "Gagal menambahkan progres: " + data.message,
                        confirmButtonText: "OK"
                    });
                }
            })
            .catch(error => {
                console.error("Error adding progress:", error);
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Terjadi kesalahan saat menambahkan progres.",
                    confirmButtonText: "OK"
                });
            });
    } else {
        Swal.fire({
            icon: "warning",
            title: "Input tidak valid",
            text: "Masukkan angka yang valid!",
            confirmButtonText: "OK"
        });
    }
}
