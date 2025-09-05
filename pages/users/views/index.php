<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Users Table</h5>

            <!-- Users Table -->
            <div class="card shadow-sm rounded-3 border-0" style="max-width: 90%;">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i> Users</h5>
                </div>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 5%;">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col" class="text-center" style="width: 15%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <th scope="row"><?= ++$i ?></th>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td>
                                        <a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="text-decoration-none">
                                            <i class="bi bi-envelope-fill me-1 text-secondary"></i><?= htmlspecialchars($row['email']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="tel:<?= htmlspecialchars($row['phone']) ?>" class="text-decoration-none">
                                            <i class="bi bi-telephone-fill me-1 text-success"></i><?= htmlspecialchars($row['phone']) ?>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="?action=edit&id=<?= $row['id'] ?>" class="btn btn-outline-success btn-sm me-1" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <form action="." method="post" class="d-inline">
                                            <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button onclick="confirmDelete(this)" type="button" class="btn btn-outline-danger btn-sm" title="Delete">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- End Users Table -->
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let confirmDelete = (button) => {

        let form = button.closest('form');

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                console.log(form.submit);


                form.submit();
            }
        });
    }
</script>