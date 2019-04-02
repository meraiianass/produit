<?php 
include_once '../config2.php';
class CategorieC{
public function ajouterCategorie($categorie)
	{
		try{
			
			$c = new Config();
			$driver = $c->getConnexion();
			$stmt = $driver->prepare('INSERT INTO `categorie`(`nom_categorie`) VALUES (:n)');
			$n = $categorie->getNom();
			
			$stmt->bindparam(':n', $n);
			

			$stmt->execute();

		}catch(PDOException $ex){
			echo "Erreur: ".$ex->getMessage();
		}
	}
	public function afficherCategorieParId($id){
		try{

			$c = new Config();
			$driver = $c->getConnexion();
			$stmt = $driver->prepare('SELECT * FROM categorie WHERE id_categorie = :id');
			$stmt->bindparam(":id",$id);
			$stmt->execute();
		 	return $stmt->fetch(PDO::FETCH_ASSOC);

		}catch(PDOException $ex){
			echo "Erreur: ".$ex->getMessage();
		}
	}

	public function   afficherCategorie()
	{
		try{

			$c = new Config();
			$driver = $c->getConnexion();
			$stmt = $driver->prepare('SELECT * FROM categorie ');
			$stmt->execute();
			$categories=$stmt->fetchAll();
			
			return $categories;

		}catch(PDOException $ex){
			echo "Erreur: ".$ex->getMessage();
		}

	}

		public function supprimerCategorie($id){
		try{

			$c = new Config();
			$driver = $c->getConnexion();
			$stmt = $driver->prepare('DELETE FROM categorie WHERE id_categorie = :id');
			$stmt->bindparam(":id",$id);
			if($stmt->execute())
				header('location: affichercategorie.php');

		}catch(PDOException $ex){
			echo "Erreur: ".$ex->getMessage();
		}
	}

	public function ModifierCategorie($categorie){
		try{
			$n = $categorie->getNom();
			$i = $categorie->getId();

			$c = new Config();
			$driver = $c->getConnexion();
			$stmt = $driver->prepare('UPDATE `categorie` SET `nom_categorie`=:n  WHERE id_categorie = :id');
			
			$stmt->bindparam(':n', $n);
			$stmt->bindparam(':id', $i);
			if($stmt->execute())
				header('location: affichercategorie.php');

		}catch(PDOException $ex){
			echo "Erreur: ".$ex->getMessage();
		}
	}
}

 ?>