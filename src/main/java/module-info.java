module ngnvzn.com.ngnvzn_brand {
    requires javafx.controls;
    requires javafx.fxml;

    requires org.kordamp.bootstrapfx.core;
    requires java.sql;
    requires com.google.gson;

    opens ngnvzn.com.ngnvzn_brand to javafx.fxml;
    exports ngnvzn.com.ngnvzn_brand;
}