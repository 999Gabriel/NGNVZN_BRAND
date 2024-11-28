package ngnvzn.com.ngnvzn_brand;

import javafx.application.Application;
import javafx.geometry.Insets;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.GridPane;
import javafx.scene.paint.Color;
import javafx.stage.Stage;

import java.util.Objects;

public class ProductInserterApp extends Application
{

    @Override
    public void start(Stage primaryStage) {
        primaryStage.setTitle("Product Inserter");

        // Erstelle das Layout
        GridPane grid = new GridPane();
        grid.setPadding(new Insets(10, 10, 10, 10));
        grid.setVgap(8);
        grid.setHgap(10);

        // Logo-Bild laden
        //Image logoImage = new Image(Objects.requireNonNull(getClass().getResourceAsStream("./img/ngnvzn.png")));
        //ImageView logoImageView = new ImageView(logoImage);
        //logoImageView.setFitWidth(100);  // Passe die Größe des Logos an
        //logoImageView.setPreserveRatio(true);
        //GridPane.setConstraints(logoImageView, 0, 0, 2, 1);  // Das Logo soll die ersten beiden Spalten überlappen

        // UI-Komponenten erstellen
        Label nameLabel = new Label("Product Name:");
        GridPane.setConstraints(nameLabel, 0, 1);
        TextField nameInput = new TextField();
        GridPane.setConstraints(nameInput, 1, 1);

        Label descriptionLabel = new Label("Description:");
        GridPane.setConstraints(descriptionLabel, 0, 2);
        TextField descriptionInput = new TextField();
        GridPane.setConstraints(descriptionInput, 1, 2);

        Label priceLabel = new Label("Price:");
        GridPane.setConstraints(priceLabel, 0, 3);
        TextField priceInput = new TextField();
        GridPane.setConstraints(priceInput, 1, 3);

        Label imageUrlLabel = new Label("Image URL:");
        GridPane.setConstraints(imageUrlLabel, 0, 4);
        TextField imageUrlInput = new TextField();
        GridPane.setConstraints(imageUrlInput, 1, 4);

        Label stockQuantityLabel = new Label("Stock Quantity:");
        GridPane.setConstraints(stockQuantityLabel, 0, 5);
        TextField stockQuantityInput = new TextField();
        GridPane.setConstraints(stockQuantityInput, 1, 5);

        Button submitButton = new Button("Submit");
        GridPane.setConstraints(submitButton, 1, 6);

        // Füge alle Komponenten zum Layout hinzu
        grid.getChildren().addAll(nameLabel, nameInput, descriptionLabel, descriptionInput,
                priceLabel, priceInput, imageUrlLabel, imageUrlInput,
                stockQuantityLabel, stockQuantityInput, submitButton);

        // Hintergrundfarbe des GridPane setzen
        grid.setStyle("-fx-background-color: #f4f4f4;");

        // UI-Styles setzen
        nameLabel.setStyle("-fx-font-weight: bold; -fx-text-fill: #003366;");
        descriptionLabel.setStyle("-fx-font-weight: bold; -fx-text-fill: #003366;");
        priceLabel.setStyle("-fx-font-weight: bold; -fx-text-fill: #003366;");
        imageUrlLabel.setStyle("-fx-font-weight: bold; -fx-text-fill: #003366;");
        stockQuantityLabel.setStyle("-fx-font-weight: bold; -fx-text-fill: #003366;");
        submitButton.setStyle("-fx-background-color: #3366cc; -fx-text-fill: white;");

        // Szene erstellen
        Scene scene = new Scene(grid, 400, 350, Color.web("#f4f4f4"));

        submitButton.setOnAction(e -> {
            String name = nameInput.getText();
            String description = descriptionInput.getText();
            double price = Double.parseDouble(priceInput.getText());
            String imageUrl = imageUrlInput.getText();
            int stockQuantity = Integer.parseInt(stockQuantityInput.getText());

            try {
                ProductInserter.insertProduct(name, description, price, imageUrl, stockQuantity);
                showAlert("Success", "Product inserted successfully!");
            } catch (Exception ex) {
                showAlert("Error", "Failed to insert product: " + ex.getMessage());
            }
        });

        primaryStage.setScene(scene);
        primaryStage.show();
    }

    private void showAlert(String title, String message) {
        Alert alert = new Alert(Alert.AlertType.INFORMATION);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.showAndWait();
    }

    public static void main(String[] args) {
        launch(args);
    }
}