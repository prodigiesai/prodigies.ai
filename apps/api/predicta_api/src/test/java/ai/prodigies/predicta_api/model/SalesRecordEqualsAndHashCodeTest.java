package ai.prodigies.predicta_api.model;

import org.junit.jupiter.api.Test;
import org.springframework.boot.autoconfigure.data.mongo.MongoRepositoriesAutoConfiguration;
import org.springframework.boot.autoconfigure.mongo.MongoAutoConfiguration;
import org.springframework.boot.test.autoconfigure.orm.jpa.DataJpaTest;
import org.springframework.test.context.ActiveProfiles;

import static org.assertj.core.api.Assertions.assertThat;

@DataJpaTest(excludeAutoConfiguration = {MongoAutoConfiguration.class, MongoRepositoriesAutoConfiguration.class})
@ActiveProfiles("test")
public class SalesRecordEqualsAndHashCodeTest {

    @Test
    void shouldReturnTrueForEqualObjects() {
        // Arrange
        SalesRecord record1 = SalesRecord.builder()
                .id(1L)
                .customerName("John Doe")
                .productName("Product A")
                .quantity(10)
                .price(100.0)
                .build();

        SalesRecord record2 = SalesRecord.builder()
                .id(1L)
                .customerName("John Doe")
                .productName("Product A")
                .quantity(10)
                .price(100.0)
                .build();

        // Assert
        assertThat(record1).isEqualTo(record2);
        assertThat(record1.hashCode()).isEqualTo(record2.hashCode());
    }

    @Test
    void shouldReturnFalseForDifferentObjects() {
        // Arrange
        SalesRecord record1 = SalesRecord.builder()
                .id(1L)
                .customerName("John Doe")
                .productName("Product A")
                .quantity(10)
                .price(100.0)
                .build();

        SalesRecord record2 = SalesRecord.builder()
                .id(2L)
                .customerName("Jane Smith")
                .productName("Product B")
                .quantity(5)
                .price(50.0)
                .build();

        // Assert
        assertThat(record1).isNotEqualTo(record2);
    }
}