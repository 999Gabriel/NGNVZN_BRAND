package ngnvzn.com.ngnvzn_brand;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;

public class ProductInserter {

    private static final String DB_URL = "jdbc:mysql://localhost:3306/ngnvzn_shop?useSSL=false&serverTimezone=UTC&allowPublicKeyRetrieval=true";
    private static final String USER = "root";
    private static final String PASS = "macintosh";

    public static void insertProduct(String name, String description, double price, String imageUrl, int stockQuantity) throws SQLException {
        String query = "INSERT INTO products (name, description, price, imageUrl, stockQuantity, created_at) VALUES (?, ?, ?, ?, ?, NOW())";

        try (Connection connection = DriverManager.getConnection(DB_URL, USER, PASS);
             PreparedStatement statement = connection.prepareStatement(query)) {

            statement.setString(1, name);
            statement.setString(2, description);
            statement.setDouble(3, price);
            statement.setString(4, imageUrl);
            statement.setInt(5, stockQuantity);

            statement.executeUpdate();
        }
    }
}