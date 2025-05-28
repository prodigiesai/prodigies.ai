package ai.prodigies.predicta_api.model;

import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.validation.constraints.Min;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.Positive;
// Ensure Lombok dependency is added to the project
import lombok.Builder;
import lombok.Getter;
import lombok.Setter;
import lombok.Data;
import lombok.EqualsAndHashCode;
import lombok.NoArgsConstructor;

@Setter
@Getter
@Builder
@Entity
@EqualsAndHashCode
@Data
@NoArgsConstructor
public class SalesRecord {

    // Getters and Setters
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @NotBlank(message = "Customer name is required")
    private String customerName;

    @NotBlank(message = "Product name is required")
    private String productName;

    @Positive(message = "Quantity must be a positive number")
    private int quantity;

    @Min(value = 1, message = "Price must be at least 1")
    private double price;


    // Explicit public constructor
    public SalesRecord(Long id, String customerName, String productName, int quantity, double price) {
        this.id = id;
        this.customerName = customerName;
        this.productName = productName;
        this.quantity = quantity;
        this.price = price;
    }
}