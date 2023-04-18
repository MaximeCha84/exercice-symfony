import React from "react";
import ReactDOM from "react-dom";
import axios from "axios";
import "./css/modal.css";

const ModalUser = ({ open, onClose }) => {
	if (!open) return null;

	const handleSubmit = async () => {
		e.preventDefault();

		const newUser = {};
		newUser.lastName = e.target.lastName.value;
		newUser.firstName = e.target.firstName.value;
		newUser.email = e.target.email.value;
		newUser.address =
			e.target.zipCode.value +
			" " +
			e.target.city.value +
			", " +
			e.target.address.value;
		newUser.phoneNumber = e.target.phoneNumber.value;
		newUser.birthDate = e.target.birthDate.value;

		axios({ method: "post", url: "/add/user", data: newUser })
			.then((response) => {
				console.log(response);
			})
			.catch((error) => {
				console.log(error);
			});

		window.location.reload();
	};

	return ReactDOM.createPortal(
		<div className="overlay">
			<div className="modal-user rounded">
				<h1 className="mb-3">Ajouter un utilisateur</h1>

				<div className="container">
					<form onSubmit={handleSubmit}>
						<div className="row mb-2">
							<div className="col">
								<label htmlFor="lastName" className="form-label">
									Nom
								</label>
								<input
									type="text"
									id="lastName"
									className="form-control"
									maxLength="40"
									pattern="^[a-zA-Z\u00C0-\u00FF]*$"
									required
								/>
							</div>
							<div className="col">
								<label htmlFor="firstName" className="form-label">
									Prénom
								</label>
								<input
									type="text"
									id="firstName"
									className="form-control"
									maxLength="40"
									pattern="^[a-zA-Z\u00C0-\u00FF]*$"
									required
								/>
							</div>
						</div>
						<label htmlFor="email" className="form-label">
							Email
						</label>
						<input
							type="email"
							id="email"
							className="form-control mb-2"
							maxLength="40"
							required
						/>
						<div className="row mb-2">
							<div className="col">
								<label htmlFor="zipCode" className="form-label">
									Code postal
								</label>
								<input
									type="text"
									id="zipCode"
									className="form-control"
									required
								/>
							</div>
							<div className="col">
								<label htmlFor="city" className="form-label">
									Ville
								</label>
								<input
									type="text"
									id="city"
									className="form-control"
									maxLength="40"
									required
								/>
							</div>
						</div>
						<label htmlFor="address" className="form-label">
							Adresse
						</label>
						<input
							type="text"
							id="address"
							className="form-control mb-2"
							required
						/>

						<label htmlFor="phoneNumber" className="form-label">
							Téléphone
						</label>
						<input
							type="text"
							id="phoneNumber"
							className="form-control mb-2"
							maxLength="40"
							minLength={8}
							pattern="^((\+)33|(\+)41|0)[1-9](\d{2}){4}$"
							required
						/>

						<label htmlFor="birthDate" className="form-label">
							Date de naissance
						</label>
						<input
							type="date"
							className="form-control mb-3"
							id="birthDate"
							required
						/>

						<div className="row">
							<button
								className="btn btn-danger col mx-3"
								onClick={(e) => {
									e.preventDefault();
									onClose();
								}}
							>
								Annuler
							</button>
							<button type="submit" className="btn btn-success col mx-3">
								Ajouter
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>,
		document.getElementById("portal")
	);
};
export default ModalUser;
