package ai.prodigies.predicta_api.model;

import jakarta.validation.ConstraintViolation;
import jakarta.validation.Validation;
import jakarta.validation.Validator;
import jakarta.validation.ValidatorFactory;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import org.springframework.boot.autoconfigure.data.mongo.MongoRepositoriesAutoConfiguration;
import org.springframework.boot.autoconfigure.mongo.MongoAutoConfiguration;
import org.springframework.boot.test.autoconfigure.orm.jpa.DataJpaTest;
import org.springframework.test.context.ActiveProfiles;

import java.util.Set;

import static org.assertj.core.api.Assertions.assertThat;

@DataJpaTest(excludeAutoConfiguration = {MongoAutoConfiguration.class, MongoRepositoriesAutoConfiguration.class})
@ActiveProfiles("test")
public class SalesRecordValidationTest {

    private Validator validator;

    @BeforeEach
    void setUp() {
        ValidatorFactory factory = Validation.buildDefaultValidatorFactory();
        validator = factory.getValidator();
    }

    @Test
    void shouldFailValidationWhenCustomerNameIsBlank() {
        // Arrange
        SalesRecord salesRecord = SalesRecord.builder()
                .productName("Product A")
                .quantity(5)
                .price(100.0)
                .build();

        // Act
        Set<ConstraintViolation<SalesRecord>> violations = validator.validate(salesRecord);

        // Assert
        assertThat(violations).hasSize(1);
        assertThat(violations.iterator().next().getMessage()).isEqualTo("Customer name is required");
    }

    @Test
    void shouldFailValidationWhenProductNameIsBlank() {
        // Arrange
        SalesRecord salesRecord = SalesRecord.builder()
                .customerName("John Doe")
                .quantity(5)
                .price(100.0)
                .build();

        // Act
        Set<ConstraintViolation<SalesRecord>> violations = validator.validate(salesRecord);

        // Assert
        assertThat(violations).hasSize(1);
        assertThat(violations.iterator().next().getMessage()).isEqualTo("Product name is required");
    }

    @Test
    void shouldFailValidationWhenQuantityIsNotPositive() {
        // Arrange
        SalesRecord salesRecord = SalesRecord.builder()
                .customerName("John Doe")
                .productName("Product A")
                .quantity(0)
                .price(100.0)
                .build();

        // Act
        Set<ConstraintViolation<SalesRecord>> violations = validator.validate(salesRecord);

        // Assert
        assertThat(violations).hasSize(1);
        assertThat(violations.iterator().next().getMessage()).isEqualTo("Quantity must be a positive number");
    }

    @Test
    void shouldFailValidationWhenPriceIsLessThanOne() {
        // Arrange
        SalesRecord salesRecord = SalesRecord.builder()
                .customerName("John Doe")
                .productName("Product A")
                .quantity(5)
                .price(0.5) // Invalid price
                .build();

        // Act
        Set<ConstraintViolation<SalesRecord>> violations = validator.validate(salesRecord);

        // Assert
        assertThat(violations).hasSize(1);
        assertThat(violations.iterator().next().getMessage()).isEqualTo("Price must be at least 1");
    }

    @Test
    void shouldPassValidationWhenAllFieldsAreValid() {
        // Arrange
        SalesRecord salesRecord = SalesRecord.builder()
                .customerName("John Doe")
                .productName("Product A")
                .quantity(5)
                .price(100.0)
                .build();

        // Act
        Set<ConstraintViolation<SalesRecord>> violations = validator.validate(salesRecord);

        // Assert
        assertThat(violations).isEmpty();
    }
}