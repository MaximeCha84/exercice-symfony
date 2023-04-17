import React, { useState } from "react";
import DashboardUsers from "./DashboardUsers";
import ModalUser from "./ModalUser";

const Dashboard = (props) => {
	const [isOpen, setIsOpen] = useState(false);

	return (
		<div>
			<div id="portal"></div>
			<ModalUser open={isOpen} onClose={() => setIsOpen(false)}></ModalUser>
			<DashboardUsers
				usersList={props.usersList}
				isModalOpen={isOpen}
			></DashboardUsers>
			<button className="btn btn-success mb-3" onClick={() => setIsOpen(true)}>
				Ajouter un utilisateur
			</button>
		</div>
	);
};

export default Dashboard;
