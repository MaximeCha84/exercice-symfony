import React, { useState } from "react";

const DashboardUsers = (props) => {
	const [users, setUsers] = useState(JSON.parse(props.usersList));

	const deleteUser = (userId) => {
		fetch("/delete/user/" + userId, {
			method: "DELETE",
		})
			.then((response) => {
				if (response.status === 200) {
					setUsers((users) => users.filter((user) => user.id !== userId));
				} else {
					console.log(
						"Une erreur est survenue. Erreur : " + response.statusText
					);
				}
			})
			.catch((error) => {
				console.log(error);
			});
	};

	const displayUserList = () => {
		return users.map((user, index) => {
			return (
				<tr key={index}>
					<td
						onClick={() =>
							(window.location = "/user/" + user.id + "/properties")
						}
						style={{ cursor: "pointer" }}
					>
						{user.lastName}
					</td>
					<td>{user.firstName}</td>
					<td>
						{user.age} {user.age > 1 ? "ans" : "an"}
					</td>
					<td>{user.email}</td>
					<td>{user.adress}</td>
					<td>{user.phoneNumber}</td>
					<td>
						<button
							className="btn btn-danger"
							onClick={() => {
								deleteUser(user.id);
							}}
						>
							Supprimer
						</button>
					</td>
				</tr>
			);
		});
	};

	return (
		<table className="table table-striped table-hover">
			<thead>
				<tr>
					<th scope="col">Nom</th>
					<th scope="col">Prénom</th>
					<th scope="col">Age</th>
					<th scope="col">Email</th>
					<th scope="col">Adresse</th>
					<th scope="col">Téléphone</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				{users.length === 0 ? (
					<tr>
						<td>Pas d'utilisateurs</td>
					</tr>
				) : (
					displayUserList()
				)}
			</tbody>
		</table>
	);
};

export default DashboardUsers;
